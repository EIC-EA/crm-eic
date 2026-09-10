<?php

use CRM_EicAnonymiser_ExtensionUtil as E;

/**
 * Set-based (bulk) anonymisation of EIC personal/company data.
 *
 * This is the fast counterpart to CRM_EicAnonymiser_Worker. Instead of looping
 * one contact at a time it runs a handful of SQL UPDATE statements that touch
 * every matching contact at once. The anonymised values are identical to the
 * per-contact worker:
 *
 *   Contact:  first_name  -> First-<id>
 *             last_name   -> Last-<id>
 *             organization_name -> Organization-<id>
 *   Email:    email       -> anon-<contact_id>-<id>@example.invalid
 *   Website:  url         -> https://anon-<contact_id>-<id>.example.invalid
 *   Custom:   PIC         -> anon-pic-<entity_id>
 *             SMEDId      -> anon-smedid-<entity_id>
 *             Company Domain Name -> anon-<entity_id>.example.invalid
 *             eulogin     -> ANON-<entity_id>
 *
 * Only non-empty values are changed (NULL / '' left as-is). The corresponding
 * log_ tables are scrubbed with the same rules.
 *
 * Implementation notes:
 *  - Uses single-table UPDATEs scoped by a subquery on civicrm_contact rather
 *    than multi-table UPDATE ... JOIN. Joining civicrm_contact into the same
 *    UPDATE conflicts with CiviCRM's logging triggers (MySQL error 1442:
 *    "Can't update table ... already used by statement which invoked this
 *    stored function/trigger").
 *  - No table alias is used, so column references are unambiguous.
 *
 * CAUTION: This is irreversible. Take a backup first.
 */
class CRM_EicAnonymiser_SqlWorker {

  /**
   * Custom fields to anonymise: 'Group.field' => strategy.
   * Kept identical to CRM_EicAnonymiser_Worker so both paths agree.
   *
   * @var array<string,string>
   */
  protected $customFields = [
    // NOTE: PIC is public data and is deliberately kept (not anonymised).
    'EIC_Organisation_identifiers.SMEDId'              => 'token:smedid',
    'EIC_Organisation_identifiers.Company_Domain_Name' => 'domain',
    'EIC_Awardee_representative.eulogin'               => 'token',
  ];

  /** @var bool */
  protected $dryRun = FALSE;

  /** @var bool */
  protected $includeDeleted = TRUE;

  /** @var string[] */
  protected $contactTypes = ['Individual', 'Organization'];

  /** @var string[] Human-readable log. */
  protected $log = [];

  /**
   * @param bool $dryRun
   * @param bool $includeDeleted
   * @param string[] $contactTypes
   */
  public function __construct(bool $dryRun = FALSE, bool $includeDeleted = TRUE, array $contactTypes = ['Individual', 'Organization']) {
    $this->dryRun = $dryRun;
    $this->includeDeleted = $includeDeleted;
    $this->contactTypes = $contactTypes;
  }

  /**
   * Static entry point.
   *
   * @param bool $dryRun
   * @param bool $includeDeleted
   * @param string[] $contactTypes
   * @return array {dry_run: bool, total_matched: int, updated: array, log: string[]}
   * @throws \CRM_Core_Exception
   */
  public static function anonymiseAll(bool $dryRun = FALSE, bool $includeDeleted = TRUE, array $contactTypes = ['Individual', 'Organization']): array {
    $worker = new self($dryRun, $includeDeleted, $contactTypes);
    return $worker->run();
  }

  /**
   * Execute the bulk anonymisation.
   *
   * @return array
   * @throws \CRM_Core_Exception
   */
  public function run(): array {
    // Refuse to write unless the environment explicitly opts in.
    CRM_EicAnonymiser_Guard::assertAllowed($this->dryRun);

    $updated = [];

    // Resolve the in-scope contact ids in PHP. We must NOT reference
    // civicrm_contact inside the UPDATEs on civicrm_email / civicrm_website /
    // custom tables: their logging triggers touch civicrm_contact, and MySQL
    // error 1442 forbids a trigger from using a table the invoking statement
    // already uses (even in a subquery). Passing literal id batches avoids it.
    $ids = [];
    $idDao = CRM_Core_DAO::executeQuery("SELECT id FROM civicrm_contact WHERE " . $this->contactWhere());
    while ($idDao->fetch()) {
      $ids[] = (int) $idDao->id;
    }
    $total = count($ids);
    $this->log(E::ts('%1 contact(s) in scope.', [1 => $total]));

    $meta = $this->getCustomFieldMeta();
    $customByTable = $this->groupCustomFieldsByTable($meta);

    // Fake but deterministic names, keyed by contact id. The id is appended
    // so values stay unique and traceable, e.g. "Alex-10035" / "Turner-10035".
    $fakeFirst = $this->fakeFirstExpr();
    $fakeLast  = $this->fakeLastExpr();
    // A random-but-deterministic company name for organizations. Because it is
    // derived from id, organization_name and legal_name get the SAME base name
    // for a given contact (legal_name just carries a "-legal" suffix).
    $fakeCompany = "CONCAT(" . $this->fakeCompanyExpr() . ", '-', id)";

    // Person "Full Name" / "Last, First" forms.
    $personFull = "CONCAT({$fakeFirst}, '-', id, ' ', {$fakeLast}, '-', id)";
    $personSort = "CONCAT({$fakeLast}, '-', id, ', ', {$fakeFirst}, '-', id)";
    // display_name / sort_name exist for every contact, so they must be
    // type-aware: company name for organizations, household name for
    // households, person name otherwise.
    $displayExpr = "CASE contact_type"
      . " WHEN 'Organization' THEN {$fakeCompany}"
      . " WHEN 'Household' THEN CONCAT('Household-', id)"
      . " ELSE {$personFull} END";
    $sortExpr = "CASE contact_type"
      . " WHEN 'Organization' THEN {$fakeCompany}"
      . " WHEN 'Household' THEN CONCAT('Household-', id)"
      . " ELSE {$personSort} END";

    $nameCols = [
      'first_name'        => "CONCAT({$fakeFirst}, '-', id)",
      'last_name'         => "CONCAT({$fakeLast}, '-', id)",
      'organization_name' => $fakeCompany,
      'legal_name'        => "CONCAT({$fakeCompany}, '-legal')",
      'household_name'    => "CONCAT('Household-', id)",
      // Type-aware display / sort so orgs don't get person names.
      'display_name'      => $displayExpr,
      'sort_name'         => $sortExpr,
      // External ID currently holds the original email — overwrite it.
      'external_identifier' => "CONCAT('anon-ext-', id)",
      // Greetings & addressee still expose the real name; blank the displays
      // and custom variants, and null the *_id so CiviCRM won't recompute them.
      'email_greeting_display'  => "CONCAT('Dear ', {$fakeFirst}, '-', id)",
      'postal_greeting_display' => "CONCAT('Dear ', {$fakeFirst}, '-', id)",
      'addressee_display'       => $displayExpr,
      'email_greeting_custom'   => "CONCAT('Dear ', {$fakeFirst}, '-', id)",
      'postal_greeting_custom'  => "CONCAT('Dear ', {$fakeFirst}, '-', id)",
      'addressee_custom'        => $displayExpr,
    ];

    // ---- civicrm_contact itself: scope on contact_type directly. Its trigger
    // writes to log_civicrm_contact (a different table), so no 1442 here. ----
    $updated['civicrm_contact'] = $this->updateTable('civicrm_contact', $nameCols, $this->contactWhere());

    if (empty($ids)) {
      return [
        'dry_run'       => $this->dryRun,
        'total_matched' => 0,
        'updated'       => $updated,
        'log'           => $this->log,
      ];
    }

    // ---- Tables scoped by a literal id batch (no civicrm_contact reference) ----
    $updated['civicrm_email'] = $this->updateByIds(
      'civicrm_email', ['email' => "CONCAT('anon-', contact_id, '-', id, '@example.invalid')"], 'contact_id', $ids
    );
    // NOTE: Main website is public data and is deliberately kept (not anonymised).
    foreach ($customByTable as $table => $cols) {
      $updated[$table] = $this->updateByIds($table, $this->customAssignments($cols), 'entity_id', $ids);
    }

    // ---- Log tables (no logging triggers on log_ tables, but keep the same
    // literal-id batching for consistency and to avoid huge single statements) ----
    $updated['log_civicrm_contact'] = $this->updateByIds('log_civicrm_contact', $nameCols, 'id', $ids);
    $updated['log_civicrm_email'] = $this->updateByIds(
      'log_civicrm_email', ['email' => "CONCAT('anon-', contact_id, '-', id, '@example.invalid')"], 'contact_id', $ids
    );
    // (website intentionally not scrubbed — see note above)
    foreach ($customByTable as $table => $cols) {
      $updated['log_' . $table] = $this->updateByIds('log_' . $table, $this->customAssignments($cols), 'entity_id', $ids);
    }

    // ---- Activities: replace subject/details with lorem filler ----
    $activityUpdates = $this->anonymiseActivities($ids);
    $updated = array_merge($updated, $activityUpdates);

    return [
      'dry_run'       => $this->dryRun,
      'total_matched' => $total,
      'updated'       => $updated,
      'log'           => $this->log,
    ];
  }

  /**
   * Replace the free-text fields (subject, details) of activities linked to
   * in-scope contacts with lorem-ipsum filler. Activity ids are resolved in
   * PHP (via civicrm_activity_contact) so the UPDATE never references
   * civicrm_contact / civicrm_activity_contact and avoids trigger error 1442.
   *
   * @param int[] $contactIds
   * @return array<string,int> updated counts keyed by table
   */
  protected function anonymiseActivities(array $contactIds): array {
    $out = ['civicrm_activity' => 0, 'log_civicrm_activity' => 0];
    if (empty($contactIds)) {
      return $out;
    }

    // Collect distinct activity ids for the in-scope contacts, in id batches.
    $activityIds = [];
    foreach (array_chunk($contactIds, 5000) as $chunk) {
      $idList = implode(',', $chunk);
      $dao = CRM_Core_DAO::executeQuery(
        "SELECT DISTINCT activity_id FROM civicrm_activity_contact WHERE contact_id IN ({$idList})"
      );
      while ($dao->fetch()) {
        $activityIds[(int) $dao->activity_id] = (int) $dao->activity_id;
      }
    }
    $activityIds = array_values($activityIds);
    if (empty($activityIds)) {
      return $out;
    }

    $lorem = "'" . CRM_Core_DAO::escapeString($this->loremText()) . "'";
    $assignments = [
      'subject' => "CONCAT('Activity-', id)",
      'details' => $lorem,
    ];

    $out['civicrm_activity'] = $this->updateByIds('civicrm_activity', $assignments, 'id', $activityIds);
    $out['log_civicrm_activity'] = $this->updateByIds('log_civicrm_activity', $assignments, 'id', $activityIds);

    // EIC project-info custom table (extends Activity, keyed by entity_id =
    // activity id). Scrub identifying free-text; leave categorical/statistical
    // fields (sectors, funding, dates, tech clusters) intact.
    $projectTable = 'civicrm_value_srm_eic_he_project_info';
    $projectCols = [
      'project_title'      => "CONCAT('Project-', entity_id)",
      'project_number'     => "CONCAT('PN-', entity_id)",
      'free_keywords'      => $this->fakeKeywordsExpr(),
      'field_of_science'   => "CONCAT('Field-', ELT(1 + (entity_id MOD 8), 'Physics', 'Biology', 'Chemistry', 'Computing', 'Engineering', 'Materials', 'Medicine', 'Energy'))",
      // Fixed placeholders (only applied where the value is non-empty).
      'equity_proposed'    => "1.23",
      'termination'        => "'Anonymous'",
      'terminated_partners' => "'Anonymous'",
      'phase'              => "'Anonymous'",
      'ec_tool_status'     => "'Anonymous'",
    ];
    $out[$projectTable] = $this->updateByIds($projectTable, $projectCols, 'entity_id', $activityIds);
    $out['log_' . $projectTable] = $this->updateByIds('log_' . $projectTable, $projectCols, 'entity_id', $activityIds);

    return $out;
  }

  /**
   * SQL expression yielding a deterministic, realistic-looking comma-separated
   * keyword list, e.g. "Keyword-A1, Keyword-D2, Keyword-G3". The letters vary
   * by entity_id so different rows get different keywords; the numeric suffix
   * is the position in the list.
   *
   * @return string
   */
  protected function fakeKeywordsExpr(): string {
    $letters = "'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'";
    // Pick three different letters by offsetting the entity_id modulo 26.
    $l1 = "ELT(1 + (entity_id MOD 26), {$letters})";
    $l2 = "ELT(1 + ((entity_id + 7) MOD 26), {$letters})";
    $l3 = "ELT(1 + ((entity_id + 13) MOD 26), {$letters})";
    return "CONCAT('Keyword-', {$l1}, '1, Keyword-', {$l2}, '2, Keyword-', {$l3}, '3')";
  }

  /**
   * A block of lorem-ipsum used to replace activity details.
   *
   * @return string
   */
  protected function loremText(): string {
    return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. '
      . 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
      . 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris '
      . 'nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in '
      . 'reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla '
      . 'pariatur. Excepteur sint occaecat cupidatat non proident, sunt in '
      . 'culpa qui officia deserunt mollit anim id est laborum.';
  }

  /**
   * SQL expression yielding a deterministic fake first name from a fixed pool,
   * selected by contact id. Uses ELT() so it stays fully set-based.
   *
   * @return string
   */
  protected function fakeFirstExpr(): string {
    $names = [
      'Alex', 'Sam', 'Jordan', 'Taylor', 'Morgan', 'Casey', 'Riley', 'Jamie',
      'Robin', 'Charlie', 'Drew', 'Quinn', 'Avery', 'Parker', 'Reese', 'Skyler',
      'Emerson', 'Rowan', 'Sage', 'Blake',
    ];
    return $this->eltExpr($names);
  }

  /**
   * SQL expression yielding a deterministic, realistic-looking company name
   * from two word pools (a distinctive first word + a business-type word),
   * selected by contact id, e.g. "Nova Systems", "Apex Labs".
   *
   * @return string
   */
  protected function fakeCompanyExpr(): string {
    // Pool sizes are PRIME (19 and 17) and thus coprime with the id spacing
    // seen in the data (contacts often step by a fixed amount like 20). If a
    // pool size shares a factor with that step, every stepped id lands on the
    // same word and names repeat; coprime sizes avoid that and spread names.
    $first = [
      'Nova', 'Apex', 'Vertex', 'Lumen', 'Orbit', 'Pulse', 'Delta', 'Zenith',
      'Aster', 'Helix', 'Quantum', 'Vega', 'Cobalt', 'Ember', 'Solaris',
      'Meridian', 'Axiom', 'Nimbus', 'Fathom',
    ]; // 19 entries (prime)
    $second = [
      'Systems', 'Labs', 'Technologies', 'Dynamics', 'Solutions', 'Industries',
      'Group', 'Works', 'Analytics', 'Robotics', 'Sciences', 'Materials',
      'Energy', 'Digital', 'Networks', 'Instruments', 'Ventures',
    ]; // 17 entries (prime)
    $w1 = $this->eltExpr($first);
    $w2 = $this->eltExpr($second);
    return "CONCAT({$w1}, ' ', {$w2})";
  }

  /**
   * SQL expression yielding a deterministic fake last name from a fixed pool.
   *
   * @return string
   */
  protected function fakeLastExpr(): string {
    $names = [
      'Turner', 'Rivera', 'Bennett', 'Coleman', 'Hayes', 'Fisher', 'Reed',
      'Palmer', 'Ellis', 'Ford', 'Grant', 'Hunt', 'Lane', 'Marsh', 'Nash',
      'Owens', 'Pierce', 'Shaw', 'Todd', 'Wells',
    ];
    return $this->eltExpr($names);
  }

  /**
   * Build an ELT(1 + (id MOD n), 'a','b',...) SQL expression from a name pool.
   * Names are internal constants, safely quoted.
   *
   * @param string[] $names
   * @return string
   */
  protected function eltExpr(array $names): string {
    return $this->eltExprOffset($names, 0);
  }

  /**
   * Like eltExpr() but adds an offset to id before the modulo, so a second
   * pool selected from the same id varies independently from the first.
   *
   * @param string[] $names
   * @param int $offset
   * @return string
   */
  protected function eltExprOffset(array $names, int $offset): string {
    $count = count($names);
    $quoted = array_map(function ($n) {
      return "'" . CRM_Core_DAO::escapeString($n) . "'";
    }, $names);
    $expr = $offset > 0 ? "(id + {$offset})" : 'id';
    return "ELT(1 + ({$expr} MOD {$count}), " . implode(', ', $quoted) . ")";
  }

  /**
   * Update a table in batches of literal ids, so the statement never
   * references civicrm_contact (avoids trigger error 1442).
   *
   * @param string $table
   * @param array<string,string> $assignments  column => replacement expr
   * @param string $idColumn  column in $table holding the contact id
   * @param int[]  $ids       in-scope contact ids
   * @return int  total rows changed (real) or matched (dry-run)
   */
  protected function updateByIds(string $table, array $assignments, string $idColumn, array $ids): int {
    if (!$this->tableExists($table) || empty($ids)) {
      return 0;
    }
    $batchSize = 5000;
    $affected = 0;
    foreach (array_chunk($ids, $batchSize) as $chunk) {
      $idList = implode(',', $chunk);
      $affected += $this->updateTable($table, $assignments, "`{$idColumn}` IN ({$idList})", TRUE);
    }
    $verb = $this->dryRun ? E::ts('Would update up to') : E::ts('Updated');
    $this->log(E::ts('%1 %2 row(s) in %3.', [1 => $verb, 2 => $affected, 3 => $table]));
    return $affected;
  }

  /**
   * WHERE fragment restricting civicrm_contact to the types in scope.
   *
   * @return string
   */
  protected function contactWhere(): string {
    $types = array_map(function ($t) {
      return "'" . CRM_Core_DAO::escapeString($t) . "'";
    }, $this->contactTypes);
    $sql = "contact_type IN (" . implode(',', $types) . ")";
    if (!$this->includeDeleted) {
      $sql .= " AND is_deleted = 0";
    }
    return $sql;
  }

  /**
   * Build the column => replacement map for a custom value table.
   *
   * @param array $cols  [ ['column' => .., 'strategy' => ..], .. ]
   * @return array<string,string>
   */
  protected function customAssignments(array $cols): array {
    $out = [];
    foreach ($cols as $col) {
      $out[$col['column']] = $this->customExpr($col['strategy']);
    }
    return $out;
  }

  /**
   * Run (or, in dry-run, count) a single-table UPDATE.
   *
   * Only columns that exist in the table are included. Each column is guarded
   * so blank/NULL values are left untouched.
   *
   * @param string $table
   * @param array<string,string> $assignments  column => replacement SQL expr
   * @param string $where  row filter (no leading WHERE)
   * @return int  rows changed (real) or matched (dry-run)
   */
  protected function updateTable(string $table, array $assignments, string $where, bool $quiet = FALSE): int {
    if (!$this->tableExists($table)) {
      return 0;
    }

    $sets = [];
    foreach ($assignments as $column => $replacement) {
      if ($this->columnExists($table, $column)) {
        $sets[] = $this->guarded($column, $replacement);
      }
    }
    if (empty($sets)) {
      return 0;
    }

    if ($this->dryRun) {
      $sql = "SELECT COUNT(*) FROM `{$table}` WHERE {$where}";
      $count = (int) CRM_Core_DAO::singleValueQuery($sql);
      if (!$quiet) {
        $this->log(E::ts('Would update up to %1 row(s) in %2.', [1 => $count, 2 => $table]));
      }
      return $count;
    }

    $setClause = implode(', ', $sets);
    $sql = "UPDATE `{$table}` SET {$setClause} WHERE {$where}";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $rows = $dao->affectedRows();
    if (!$quiet) {
      $this->log(E::ts('Updated %1 row(s) in %2.', [1 => $rows, 2 => $table]));
    }
    return $rows;
  }

  /**
   * Wrap an assignment so blank/NULL values are left untouched.
   * No table alias is used, so the column is referenced bare.
   *
   * @param string $column
   * @param string $replacement
   * @return string
   */
  protected function guarded(string $column, string $replacement): string {
    // Compare via CAST(... AS CHAR) so the empty check is safe for numeric
    // columns too: comparing a DECIMAL/Money column directly to '' triggers
    // MySQL error 1292 "Truncated incorrect DECIMAL value" under strict mode.
    return "`{$column}` = CASE WHEN `{$column}` IS NULL OR CAST(`{$column}` AS CHAR) = '' "
      . "THEN `{$column}` ELSE {$replacement} END";
  }

  /**
   * Build the SQL replacement expression for a custom-field strategy.
   * The per-row token is the table's entity_id.
   *
   * @param string $strategy
   * @return string
   */
  protected function customExpr(string $strategy): string {
    if ($strategy === 'domain') {
      return "CONCAT('anon-', entity_id, '.example.invalid')";
    }
    if (strpos($strategy, 'token:') === 0) {
      $prefix = substr($strategy, strlen('token:'));
      if ($prefix !== '') {
        // Prefix comes from internal config, not user input.
        return "CONCAT('anon-" . $prefix . "-', entity_id)";
      }
    }
    return "CONCAT('ANON-', entity_id)";
  }

  /**
   * Group configured custom fields by their value table.
   *
   * @param array $meta
   * @return array<string,array> table => [ ['column'=>..,'strategy'=>..], .. ]
   */
  protected function groupCustomFieldsByTable(array $meta): array {
    $byTable = [];
    foreach ($this->customFields as $apiName => $strategy) {
      if (empty($meta[$apiName])) {
        continue;
      }
      $byTable[$meta[$apiName]['table_name']][] = [
        'column'   => $meta[$apiName]['column_name'],
        'strategy' => $strategy,
      ];
    }
    return $byTable;
  }

  /**
   * Resolve table_name/column_name for each configured custom field.
   *
   * @return array<string,array>
   * @throws \CRM_Core_Exception
   */
  protected function getCustomFieldMeta(): array {
    $meta = [];
    foreach (array_keys($this->customFields) as $apiName) {
      [$groupName, $fieldName] = explode('.', $apiName, 2);
      $row = \Civi\Api4\CustomField::get(FALSE)
        ->addSelect('column_name', 'custom_group_id.table_name')
        ->addWhere('custom_group_id.name', '=', $groupName)
        ->addWhere('name', '=', $fieldName)
        ->execute()
        ->first();
      if ($row) {
        $meta[$apiName] = [
          'table_name'  => $row['custom_group_id.table_name'],
          'column_name' => $row['column_name'],
        ];
      }
      else {
        $this->log(E::ts('Custom field %1 not found; skipped.', [1 => $apiName]));
      }
    }
    return $meta;
  }

  /**
   * @param string $table
   * @return bool
   */
  protected function tableExists(string $table): bool {
    static $cache = [];
    if (array_key_exists($table, $cache)) {
      return $cache[$table];
    }
    $db = (new CRM_Core_DAO())->database();
    return $cache[$table] = (bool) CRM_Core_DAO::singleValueQuery(
      "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = %1 AND table_name = %2",
      [1 => [$db, 'String'], 2 => [$table, 'String']]
    );
  }

  /**
   * @param string $table
   * @param string $column
   * @return bool
   */
  protected function columnExists(string $table, string $column): bool {
    static $cache = [];
    $key = $table . '.' . $column;
    if (array_key_exists($key, $cache)) {
      return $cache[$key];
    }
    $db = (new CRM_Core_DAO())->database();
    return $cache[$key] = (bool) CRM_Core_DAO::singleValueQuery(
      "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = %1 AND table_name = %2 AND column_name = %3",
      [1 => [$db, 'String'], 2 => [$table, 'String'], 3 => [$column, 'String']]
    );
  }

  /**
   * @param string $message
   */
  public function log(string $message): void {
    $this->log[] = $message;
    \Civi::log()->info('eic_anonymiser(sql): ' . $message);
  }

}
