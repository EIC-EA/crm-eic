<?php

use CRM_EicAnonymiser_ExtensionUtil as E;

/**
 * Performs in-place anonymisation of EIC personal/company data.
 *
 * Unlike de.systopia.anonymiser, this worker does NOT delete related
 * records. Instead it overwrites the sensitive values with deterministic,
 * non-identifying placeholders so that referential structure and record
 * counts are preserved.
 *
 * Fields covered:
 *   Person (Individual):
 *     - first_name, last_name                     (Contact)
 *     - email                                     (Email)
 *     - website url incl. LinkedIn                (Website)
 *     - eulogin custom field                      (EIC_Awardee_representative)
 *   Company (Organization):
 *     - organization_name                         (Contact)
 *     - website url                               (Website)
 *     - PIC, SMEDId, Company Domain Name          (EIC_Organisation_identifiers)
 *
 * Plus: the corresponding rows in the log_ / extended log tables.
 *
 * Supports a dry-run mode: when enabled, no data is written. Instead the
 * worker reports, per field, the current value and the value it *would*
 * write, plus the number of log rows that *would* be scrubbed.
 *
 * CAUTION: When not in dry-run mode, this is irreversible.
 */
class CRM_EicAnonymiser_Worker {

  /**
   * Custom fields to anonymise, keyed by CiviCRM custom field API name
   * ('CustomGroupName.field_name'). The value is the placeholder strategy.
   *
   * @var array<string,string>
   */
  protected $customFields = [
    'EIC_Organisation_identifiers.PIC'          => 'token:pic',
    'EIC_Organisation_identifiers.SMEDId'       => 'token:smedid',
    'EIC_Organisation_identifiers.Company_Domain_Name' => 'domain',
    'EIC_Awardee_representative.eulogin'         => 'token',
  ];

  /** @var bool When TRUE, no data is written; the worker only reports. */
  protected $dryRun = FALSE;

  /** @var string[] Accumulated human-readable log of actions performed. */
  protected $log = [];

  /**
   * Structured record of the changes made (or that would be made in dry-run).
   * Each entry: ['target' => ..., 'field' => ..., 'from' => ..., 'to' => ...].
   *
   * @var array<int,array>
   */
  protected $changes = [];

  /**
   * Resolved custom field metadata cache (process-level, shared across all
   * Worker instances in a batch run).
   * Maps 'Group.field' => ['table_name' => ..., 'column_name' => ...].
   *
   * @var array<string,array>|null
   */
  protected static $customFieldMeta = NULL;

  /**
   * Process-level cache of information_schema existence checks.
   * Key "table" -> bool, key "table.column" -> bool.
   *
   * @var array<string,bool>
   */
  protected static $schemaCache = [];

  /**
   * @param bool $dryRun
   */
  public function __construct(bool $dryRun = FALSE) {
    $this->dryRun = $dryRun;
  }

  /**
   * Static convenience entry point.
   *
   * @param int $contactId
   * @param bool $dryRun
   * @return array {log: string[], changes: array[], dry_run: bool}
   * @throws \CRM_Core_Exception
   */
  public static function anonymise(int $contactId, bool $dryRun = FALSE): array {
    $worker = new self($dryRun);
    $worker->anonymiseContact($contactId);
    return [
      'dry_run' => $dryRun,
      'log'     => $worker->getLog(),
      'changes' => $worker->getChanges(),
    ];
  }

  /**
   * Anonymise a single contact in place (or report, in dry-run mode).
   *
   * @param int $contactId
   * @throws \CRM_Core_Exception
   */
  public function anonymiseContact(int $contactId): void {
    // Refuse to write unless the environment explicitly opts in.
    CRM_EicAnonymiser_Guard::assertAllowed($this->dryRun);

    if ($contactId <= 0) {
      throw new CRM_Core_Exception(E::ts('A valid contact ID is required.'));
    }

    $contact = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('id', 'contact_type')
      ->addWhere('id', '=', $contactId)
      ->execute()
      ->first();

    if (!$contact) {
      throw new CRM_Core_Exception(E::ts('Contact %1 not found.', [1 => $contactId]));
    }

    if ($this->dryRun) {
      $this->log(E::ts('DRY RUN — no data will be written for contact %1.', [1 => $contactId]));
    }

    $this->anonymiseContactBase($contactId, $contact['contact_type']);
    $this->anonymiseEmails($contactId);
    $this->anonymiseWebsites($contactId);
    $this->anonymiseCustomFields($contactId);
    $this->scrubLogs($contactId);

    $verb = $this->dryRun ? E::ts('Dry run') : E::ts('Anonymisation');
    $this->log(E::ts('%1 of contact %2 complete.', [1 => $verb, 2 => $contactId]));
  }

  /**
   * Overwrite (or preview) the base contact name fields.
   *
   * @param int $contactId
   * @param string $contactType
   * @throws \CRM_Core_Exception
   */
  protected function anonymiseContactBase(int $contactId, string $contactType): void {
    // Determine which fields apply for this contact type.
    if ($contactType === 'Organization') {
      $planned = ['organization_name' => "Organization-{$contactId}"];
    }
    elseif ($contactType === 'Household') {
      $planned = ['household_name' => "Household-{$contactId}"];
    }
    else {
      $planned = [
        'first_name' => "First-{$contactId}",
        'last_name'  => "Last-{$contactId}",
      ];
    }

    // Read current values for reporting.
    $current = \Civi\Api4\Contact::get(FALSE)
      ->addSelect(...array_keys($planned))
      ->addWhere('id', '=', $contactId)
      ->execute()
      ->first();

    // Only touch fields that actually hold a value.
    $toChange = [];
    foreach ($planned as $field => $to) {
      if ($this->isBlank($current[$field] ?? NULL)) {
        continue;
      }
      $this->recordChange("Contact[{$contactId}]", $field, $current[$field], $to);
      $toChange[$field] = $to;
    }

    if (!$this->dryRun && !empty($toChange)) {
      $update = \Civi\Api4\Contact::update(FALSE)->addWhere('id', '=', $contactId);
      foreach ($toChange as $field => $to) {
        $update->addValue($field, $to);
      }
      $update->execute();
    }

    $this->log(E::ts('%1 base name for %2 contact %3 (%4 field(s)).', [
      1 => $this->verbApplied(),
      2 => $contactType,
      3 => $contactId,
      4 => count($toChange),
    ]));
  }

  /**
   * Anonymise (or preview) all email addresses of the contact.
   *
   * @param int $contactId
   * @throws \CRM_Core_Exception
   */
  protected function anonymiseEmails(int $contactId): void {
    $emails = \Civi\Api4\Email::get(FALSE)
      ->addSelect('id', 'email')
      ->addWhere('contact_id', '=', $contactId)
      ->execute();

    $count = 0;
    foreach ($emails as $email) {
      if ($this->isBlank($email['email'] ?? NULL)) {
        continue;
      }
      $to = "anon-{$contactId}-{$email['id']}@example.invalid";
      $this->recordChange("Email[{$email['id']}]", 'email', $email['email'], $to);

      if (!$this->dryRun) {
        \Civi\Api4\Email::update(FALSE)
          ->addWhere('id', '=', $email['id'])
          ->addValue('email', $to)
          ->execute();
      }
      $count++;
    }
    $this->log(E::ts('%1 %2 email address(es).', [1 => $this->verbApplied(), 2 => $count]));
  }

  /**
   * Anonymise (or preview) all websites (incl. LinkedIn) of the contact.
   *
   * @param int $contactId
   * @throws \CRM_Core_Exception
   */
  protected function anonymiseWebsites(int $contactId): void {
    $websites = \Civi\Api4\Website::get(FALSE)
      ->addSelect('id', 'url')
      ->addWhere('contact_id', '=', $contactId)
      ->execute();

    $count = 0;
    foreach ($websites as $website) {
      if ($this->isBlank($website['url'] ?? NULL)) {
        continue;
      }
      $to = "https://anon-{$contactId}-{$website['id']}.example.invalid";
      $this->recordChange("Website[{$website['id']}]", 'url', $website['url'], $to);

      if (!$this->dryRun) {
        \Civi\Api4\Website::update(FALSE)
          ->addWhere('id', '=', $website['id'])
          ->addValue('url', $to)
          ->execute();
      }
      $count++;
    }
    $this->log(E::ts('%1 %2 website(s).', [1 => $this->verbApplied(), 2 => $count]));
  }

  /**
   * Anonymise (or preview) the configured custom fields in place.
   *
   * @param int $contactId
   * @throws \CRM_Core_Exception
   */
  protected function anonymiseCustomFields(int $contactId): void {
    $meta = $this->getCustomFieldMeta();

    // Fields present in this environment.
    $present = [];
    foreach ($this->customFields as $apiName => $strategy) {
      if (empty($meta[$apiName])) {
        $this->log(E::ts('Custom field %1 not found; skipped.', [1 => $apiName]));
        continue;
      }
      $present[$apiName] = $strategy;
    }

    if (empty($present)) {
      return;
    }

    // Read current values for reporting.
    $current = \Civi\Api4\Contact::get(FALSE)
      ->addSelect(...array_keys($present))
      ->addWhere('id', '=', $contactId)
      ->execute()
      ->first();

    $update = \Civi\Api4\Contact::update(FALSE)->addWhere('id', '=', $contactId);
    $applied = [];
    foreach ($present as $apiName => $strategy) {
      // Leave empty custom fields untouched.
      if ($this->isBlank($current[$apiName] ?? NULL)) {
        continue;
      }
      $to = $this->placeholder($strategy, $contactId, $apiName);
      $this->recordChange("Contact[{$contactId}]", $apiName, $current[$apiName], $to);
      $update->addValue($apiName, $to);
      $applied[] = $apiName;
    }

    if (empty($applied)) {
      $this->log(E::ts('No custom field(s) needed anonymisation.'));
      return;
    }

    if (!$this->dryRun) {
      $update->execute();
    }
    $this->log(E::ts('%1 custom field(s): %2.', [1 => $this->verbApplied(), 2 => implode(', ', $applied)]));
  }

  /**
   * Scrub (or preview) the sensitive columns from the log_ tables.
   *
   * @param int $contactId
   */
  protected function scrubLogs(int $contactId): void {
    $meta = $this->getCustomFieldMeta();

    // Base contact log. Each column is only rewritten in rows where it
    // currently holds a value (blank/NULL log entries are left untouched).
    $this->scrubLogTable('log_civicrm_contact', 'id', $contactId, [
      $this->guardedAssignment('first_name', "CONCAT('First-', id)"),
      $this->guardedAssignment('last_name', "CONCAT('Last-', id)"),
      $this->guardedAssignment('organization_name', "CONCAT('Organization-', id)"),
      $this->guardedAssignment('household_name', "CONCAT('Household-', id)"),
      $this->guardedAssignment('display_name', "CONCAT('Contact-', id)"),
      $this->guardedAssignment('sort_name', "CONCAT('Contact-', id)"),
    ]);

    // Email log.
    $this->scrubLogTable('log_civicrm_email', 'contact_id', $contactId, [
      $this->guardedAssignment('email', "CONCAT('anon-', contact_id, '-', id, '@example.invalid')"),
    ]);

    // Website log.
    $this->scrubLogTable('log_civicrm_website', 'contact_id', $contactId, [
      $this->guardedAssignment('url', "CONCAT('https://anon-', contact_id, '-', id, '.example.invalid')"),
    ]);

    // Custom value tables log (grouped by table so we can update the right columns).
    $byTable = [];
    foreach ($this->customFields as $apiName => $strategy) {
      if (empty($meta[$apiName])) {
        continue;
      }
      $table = $meta[$apiName]['table_name'];
      $column = $meta[$apiName]['column_name'];
      $byTable[$table][] = $this->logColumnAssignment($column, $strategy);
    }

    foreach ($byTable as $table => $assignments) {
      $this->scrubLogTable('log_' . $table, 'entity_id', $contactId, $assignments);
    }
  }

  /**
   * Apply (or preview) a set of column assignments to a log table.
   *
   * Column assignments are built internally (never from user input) so they
   * are safe to embed. The contact id is cast to int and bound positionally.
   *
   * @param string $logTable
   * @param string $idColumn   The column in the log table that holds the contact id.
   * @param int    $contactId
   * @param string[] $assignments SET expressions.
   */
  protected function scrubLogTable(string $logTable, string $idColumn, int $contactId, array $assignments): void {
    if (!$this->tableExists($logTable) || empty($assignments)) {
      return;
    }

    // Keep only assignments whose target column exists in this log table.
    $valid = [];
    foreach ($assignments as $assignment) {
      $column = trim(explode('=', $assignment, 2)[0]);
      if ($this->columnExists($logTable, $column)) {
        $valid[] = $assignment;
      }
    }
    if (empty($valid)) {
      return;
    }

    if ($this->dryRun) {
      // Count only rows where at least one target column currently holds a
      // value — those are the rows a real run would actually change.
      $nonEmpty = [];
      foreach ($valid as $assignment) {
        $col = trim(explode('=', $assignment, 2)[0]);
        $nonEmpty[] = "(`{$col}` IS NOT NULL AND `{$col}` <> '')";
      }
      $valueFilter = $nonEmpty ? ('(' . implode(' OR ', $nonEmpty) . ')') : '1';
      $rows = (int) CRM_Core_DAO::singleValueQuery(
        "SELECT COUNT(*) FROM `{$logTable}` WHERE `{$idColumn}` = %1 AND {$valueFilter}",
        [1 => [$contactId, 'Integer']]
      );
      $this->log(E::ts('Would scrub %1 row(s) in log table %2.', [1 => $rows, 2 => $logTable]));
      return;
    }

    $setClause = implode(', ', $valid);
    $sql = "UPDATE `{$logTable}` SET {$setClause} WHERE `{$idColumn}` = %1";
    CRM_Core_DAO::executeQuery($sql, [1 => [$contactId, 'Integer']]);
    $this->log(E::ts('Scrubbed log table %1.', [1 => $logTable]));
  }

  /**
   * Build a SET expression for a custom-value log column.
   *
   * @param string $column
   * @param string $strategy
   * @return string
   */
  protected function logColumnAssignment(string $column, string $strategy): string {
    if ($strategy === 'domain') {
      return $this->guardedAssignment($column, "CONCAT('anon-', entity_id, '.example.invalid')");
    }

    $prefix = $this->tokenPrefix($strategy);
    if ($prefix !== NULL) {
      // Prefix is built internally from config, never user input.
      return $this->guardedAssignment($column, "CONCAT('anon-{$prefix}-', entity_id)");
    }

    return $this->guardedAssignment($column, "CONCAT('ANON-', entity_id)");
  }

  /**
   * Build a SET expression that only rewrites the column in rows where it
   * currently holds a value; blank/NULL values are left as they are.
   *
   * The column name and replacement expression are built internally (never
   * from user input), so they are safe to embed in SQL.
   *
   * @param string $column       The bare column name (no backticks).
   * @param string $replacement  SQL expression for the anonymised value.
   * @return string
   */
  protected function guardedAssignment(string $column, string $replacement): string {
    return "{$column} = CASE WHEN `{$column}` IS NULL OR `{$column}` = '' "
      . "THEN `{$column}` ELSE {$replacement} END";
  }

  /**
   * Generate a deterministic placeholder for a live custom field value.
   *
   * @param string $strategy
   * @param int $contactId
   * @param string $apiName
   * @return string
   */
  protected function placeholder(string $strategy, int $contactId, string $apiName): string {
    if ($strategy === 'domain') {
      return "anon-{$contactId}.example.invalid";
    }

    // token[:prefix] -> "anon-<prefix>-<cid>", or "ANON-<cid>" when unprefixed.
    if ($strategy === 'token' || strpos($strategy, 'token:') === 0) {
      $prefix = $this->tokenPrefix($strategy);
      return $prefix === NULL ? "ANON-{$contactId}" : "anon-{$prefix}-{$contactId}";
    }

    return "ANON-{$contactId}";
  }

  /**
   * Extract the prefix from a "token:<prefix>" strategy, or NULL if none.
   *
   * @param string $strategy
   * @return string|null
   */
  protected function tokenPrefix(string $strategy): ?string {
    if (strpos($strategy, 'token:') === 0) {
      $prefix = substr($strategy, strlen('token:'));
      return $prefix !== '' ? $prefix : NULL;
    }
    return NULL;
  }

  /**
   * Record a single (planned) field change for structured reporting.
   *
   * @param string $target
   * @param string $field
   * @param mixed $from
   * @param mixed $to
   */
  protected function recordChange(string $target, string $field, $from, $to): void {
    $this->changes[] = [
      'target' => $target,
      'field'  => $field,
      'from'   => $from,
      'to'     => $to,
    ];
  }

  /**
   * Verb used in log messages depending on dry-run mode.
   *
   * @return string
   */
  protected function verbApplied(): string {
    return $this->dryRun ? E::ts('Would anonymise') : E::ts('Anonymised');
  }

  /**
   * Whether a stored value is empty and should therefore be left untouched.
   *
   * NULL, missing, and empty-string values are treated as "nothing to
   * anonymise". Note: the literal string "0" is NOT considered blank.
   *
   * @param mixed $value
   * @return bool
   */
  protected function isBlank($value): bool {
    return $value === NULL || $value === '';
  }

  /**
   * Resolve the table_name/column_name for each configured custom field.
   *
   * @return array<string,array>
   * @throws \CRM_Core_Exception
   */
  protected function getCustomFieldMeta(): array {
    if (self::$customFieldMeta !== NULL) {
      return self::$customFieldMeta;
    }

    self::$customFieldMeta = [];
    foreach (array_keys($this->customFields) as $apiName) {
      [$groupName, $fieldName] = explode('.', $apiName, 2);
      $row = \Civi\Api4\CustomField::get(FALSE)
        ->addSelect('column_name', 'custom_group_id.table_name')
        ->addWhere('custom_group_id.name', '=', $groupName)
        ->addWhere('name', '=', $fieldName)
        ->execute()
        ->first();

      if ($row) {
        self::$customFieldMeta[$apiName] = [
          'table_name'  => $row['custom_group_id.table_name'],
          'column_name' => $row['column_name'],
        ];
      }
    }

    return self::$customFieldMeta;
  }

  /**
   * @param string $table
   * @return bool
   */
  protected function tableExists(string $table): bool {
    if (array_key_exists($table, self::$schemaCache)) {
      return self::$schemaCache[$table];
    }
    $dao = new CRM_Core_DAO();
    $db = $dao->database();
    $exists = (bool) CRM_Core_DAO::singleValueQuery(
      "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = %1 AND table_name = %2",
      [1 => [$db, 'String'], 2 => [$table, 'String']]
    );
    return self::$schemaCache[$table] = $exists;
  }

  /**
   * @param string $table
   * @param string $column
   * @return bool
   */
  protected function columnExists(string $table, string $column): bool {
    $key = $table . '.' . $column;
    if (array_key_exists($key, self::$schemaCache)) {
      return self::$schemaCache[$key];
    }
    $dao = new CRM_Core_DAO();
    $db = $dao->database();
    $exists = (bool) CRM_Core_DAO::singleValueQuery(
      "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = %1 AND table_name = %2 AND column_name = %3",
      [1 => [$db, 'String'], 2 => [$table, 'String'], 3 => [$column, 'String']]
    );
    return self::$schemaCache[$key] = $exists;
  }

  /**
   * @param string $message
   */
  public function log(string $message): void {
    $this->log[] = $message;
    \Civi::log()->info('eic_anonymiser: ' . $message);
  }

  /**
   * @return string[]
   */
  public function getLog(): array {
    return $this->log;
  }

  /**
   * @return array<int,array>
   */
  public function getChanges(): array {
    return $this->changes;
  }

}
