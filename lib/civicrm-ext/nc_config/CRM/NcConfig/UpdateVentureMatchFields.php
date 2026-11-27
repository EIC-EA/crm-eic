<?php

/**
 * One-time script: Update VentureMatch fields for EIC Awardees from GoogleSheetSingleRow.json.
 *
 * Execute via:
 *   cv php:eval 'CRM_NcConfig_UpdateVentureMatchFields::create()->run();'
 */

use CRM_NcConfig_ExtensionUtil as E;

class CRM_NcConfig_UpdateVentureMatchFields {

  /**
   * Mappings from eic_vm_google_sheet_form_processor.json (MapValue actions).
   */
  private const MAP_PITCH_DECK = [
    '1: Draft' => 1,
    '2: Needs Major Rework' => 2,
    '3: Decent but with Gaps' => 3,
    '4: Strong' => 4,
    '5: Investor-grade' => 5,
  ];

  private const MAP_C_LEVEL_TEAM = [
    '1: Draft' => 1,
    '2: Knows Basics' => 2,
    '3: Can Pitch' => 3,
    '4: Strong' => 4,
    '5: Exceptional' => 5,
  ];

  private const MAP_DATA_ROOM = [
    'Data Needed' => 1,
    '2: Partialy Organized' => 2,
    '3: Organized but gaps' => 3,
    '4: Complete' => 4,
    '5: Exemplary' => 5,
  ];

  private const MAP_FINANCIAL_MODEL = [
    'Data Needed' => 1,
    '2: Incomplete' => 2,
    '3: Reasonable' => 3,
    '4: Complete' => 4,
    '4: Detailed' => 4,
    '5: Audit Ready' => 5,
  ];

  /**
   * Status mapping: Google Sheet value → CiviCRM case status name.
   */
  private const MAP_STATUS = [
    'Calibration Scheduled' => 'Calibration',
    'Done' => 'Done',
    'Lost - Never heard back' => 'Lost',
    'Lost - WP1' => 'Lost',
    'Waiting for StartUp Reply' => 'Waiting EU Survey answer',
    'Waiting on Startup / Reprioritising' => 'Reprioritising',
    'WP1 - Support' => 'Material refinement',
    'WP2 - Investor Support' => 'Investors Outreach',
  ];

  /**
   * Case type machine name for VentureMatch.
   */
  private const CASE_TYPE_NAME = 'eic_sr_venturematch';

  /**
   * Relationship type machine name (name_a_b) for IA link.
   */
  private const RELATIONSHIP_IA_NAME = 'Investment Associate is';

  /**
   * Path to the JSON file.
   */
  private string $jsonFile;

  /**
   * Logger instance.
   */
  private $log;

  /**
   * @return static
   */
  public static function create(?string $jsonFile = NULL): static {
    return new static($jsonFile);
  }

  public function __construct(?string $jsonFile = NULL) {
    if ($jsonFile === NULL) {
      $jsonFile = E::path('/assets/GoogleSheetSingleRow.json');
      if (FALSE === $jsonFile) {
        throw new \RuntimeException('Could not resolve path to /assets/GoogleSheetSingleRow.json');
      }
    }
    $this->jsonFile = $jsonFile;
    $this->log = \Civi::log();
  }

  /**
   * Execute the update.
   */
  public function run(): void {
    \CRM_Core_Session::singleton()->set('userID', 1);

    $this->log->info('Start UpdateVentureMatchFields');

    if (!file_exists($this->jsonFile)) {
      throw new Exception("JSON file not found: {$this->jsonFile}");
    }

    $rows = json_decode(file_get_contents($this->jsonFile), TRUE);
    if (!is_array($rows)) {
      throw new Exception("Could not parse JSON file: {$this->jsonFile}");
    }

    echo "Loaded " . count($rows) . " row(s) from GoogleSheetSingleRow.json\n\n";

    $updated = 0;
    $skipped = 0;

    foreach ($rows as $i => $row) {
      $result = $this->processRow($i, $row);
      if ($result === 'updated') {
        $updated++;
      }
      else {
        $skipped++;
      }
    }

    $summary = "Done — Updated: {$updated}, Skipped: {$skipped}, Total: " . count($rows);
    echo "\n{$summary}\n";
    $this->log->info("Finished UpdateVentureMatchFields — {$summary}");
  }

  /**
   * Process a single row: find contact by PIC, update fields, process case.
   *
   * @return string 'updated' or 'skipped'
   */
  private function processRow(int $index, array $row): string {
    $pic = $row['PIC'] ?? NULL;
    if ($pic === NULL || $pic === '') {
      echo "[SKIP] Row {$index}: No PIC value\n";
      $this->log->warning("Row {$index}: No PIC value, skipping.");
      return 'skipped';
    }

    $contact = $this->findAwardeeByPic((string) $pic);
    if (!$contact) {
      echo "[SKIP] Row {$index}: PIC={$pic} — no EIC_Awardee contact found\n";
      $this->log->warning("Row {$index}: PIC={$pic} — no EIC_Awardee contact found, skipping.");
      return 'skipped';
    }

    // Update VentureMatch custom fields on the contact.
    $values = $this->mapValues($row);

    \Civi\Api4\Contact::update(FALSE)
      ->addWhere('id', '=', $contact['id'])
      ->addValue('VentureMatch_Fields.Capacity', $values['capacity'])
      ->addValue('VentureMatch_Fields.Pitch_Deck2', $values['pitch_deck'])
      ->addValue('VentureMatch_Fields.C_Level_Team2', $values['c_level_team'])
      ->addValue('VentureMatch_Fields.Data_Room2', $values['data_room'])
      ->addValue('VentureMatch_Fields.Financial_Model2', $values['financial_model'])
      ->execute();

    // Create a note if the Note field is not empty.
    $note = $row['Note'] ?? NULL;
    if ($note !== NULL && $note !== '') {
      $this->createNote($contact['id'], $note);
    }

    // Process the VentureMatch case.
    $case = $this->processCase($contact['id'], (string) $pic, $row);
    $caseInfo = $case
      ? "Case #{$case['id']}"
      : "Case: (none)";

    // Process the calibration meeting activity (if applicable).
    if ($case) {
      $calibrationInfo = $this->processCalibrationActivity($case['id'], $row);
      $caseInfo .= ", {$calibrationInfo}";
    }

    // Create first reminder task (if applicable).
    if ($case) {
      $reminderInfo = $this->processFirstReminder($case['id'], $row);
      $caseInfo .= ", {$reminderInfo}";
    }

    // Add IA as a case role via "Investment Associate is" relationship.
    if ($case) {
      $iaInfo = $this->linkIAtoCase($contact['id'], $case['id'], $row);
      $caseInfo .= ", {$iaInfo}";
    }

    // Process checkin tasks (Last_Checkin + Next_Checkin).
    if ($case) {
      $checkinInfo = $this->processCheckins($case['id'], $row);
      $caseInfo .= ", {$checkinInfo}";
    }

    // Create engagement cases for investors.
    $investorInfo = $this->processInvestors($contact['id'], $contact['display_name'], $row);
    $caseInfo .= ", {$investorInfo}";

    $msg = "Row {$index}: PIC={$pic} → Contact #{$contact['id']} ({$contact['display_name']}) — "
      . "Capacity=" . var_export($values['capacity'], TRUE)
      . ", PitchDeck=" . var_export($values['pitch_deck'], TRUE)
      . ", CLevelTeam=" . var_export($values['c_level_team'], TRUE)
      . ", DataRoom=" . var_export($values['data_room'], TRUE)
      . ", FinancialModel=" . var_export($values['financial_model'], TRUE)
      . " | {$caseInfo}";

    echo "[OK] {$msg}\n";
    $this->log->info($msg);

    return 'updated';
  }

  /**
   * Find an EIC Awardee contact by PIC number.
   *
   * @return array|null Contact array with 'id' and 'display_name', or NULL.
   */
  private function findAwardeeByPic(string $pic): ?array {
    return \Civi\Api4\Contact::get(FALSE)
      ->addSelect('id', 'display_name')
      ->addWhere('EIC_Organisation_identifiers.PIC', '=', $pic)
      ->addWhere('contact_sub_type', 'IN', ['EIC_Awardee'])
      ->execute()
      ->first()
      ?: NULL;
  }

  /**
   * Map the raw JSON row values to the stored field values.
   *
   * NULL if not found in mapping — mirrors FP "not_found: delete".
   */
  private function mapValues(array $row): array {
    return [
      'capacity' => ($row['Capacity'] ?? NULL) !== NULL ? (string) $row['Capacity'] : NULL,
      'pitch_deck' => self::MAP_PITCH_DECK[$row['Pitch Deck'] ?? ''] ?? NULL,
      'c_level_team' => self::MAP_C_LEVEL_TEAM[$row['C-Level / Team'] ?? ''] ?? NULL,
      'data_room' => self::MAP_DATA_ROOM[$row['Data Room'] ?? ''] ?? NULL,
      'financial_model' => self::MAP_FINANCIAL_MODEL[$row['Financial Model'] ?? ''] ?? NULL,
    ];
  }

  /**
   * Create a note on the contact.
   */
  private function createNote(int $contactId, string $note): void {
    \Civi\Api4\Note::create(FALSE)
      ->addValue('entity_id', $contactId)
      ->addValue('entity_table', 'civicrm_contact')
      ->addValue('subject', 'Fundraising note')
      ->addValue('note', $note)
      ->addValue('privacy', 'None')
      ->execute();
  }

  /**
   * Find or create the VentureMatch case for the awardee, then update it.
   *
   * Lookup strategy:
   *   1. Try finding by PIC on the case (EIC_Awardee_information.Organisation_PIC_number)
   *   2. Fallback: find by client contact_id via CaseContact
   *   3. If multiple, keep the most recent (by start_date)
   *   4. If none found, create a new case
   *
   * @return array|null The case array (with 'id', 'start_date', 'status_id:name'), or NULL.
   */
  private function processCase(int $contactId, string $pic, array $row): ?array {
    $startDate = $this->parseDate($row['Date'] ?? NULL);
    $statusName = self::MAP_STATUS[$row['Status'] ?? ''] ?? NULL;

    // 1. Try finding the case by PIC on the case custom field.
    $case = $this->findCaseByPic($pic);

    // 2. Fallback: find by client contact_id.
    if (!$case) {
      $case = $this->findCaseByClientId($contactId);
    }

    // 3. If not found, create a new case.
    if (!$case) {
      $case = $this->createCase($contactId, $startDate, $statusName);
      $this->log->info("Created new VentureMatch case #{$case['id']} for contact {$contactId}.");
    }

    // 4. Update the case (start_date + status).
    $updateQuery = \Civi\Api4\CiviCase::update(FALSE)
      ->addWhere('id', '=', $case['id']);
    if ($startDate !== NULL) {
      $updateQuery->addValue('start_date', $startDate);
    }
    if ($statusName !== NULL) {
      $updateQuery->addValue('status_id:name', $statusName);
    }
    $updateQuery->execute();

    return $case;
  }

  /**
   * Find a VentureMatch case by PIC on the case custom field.
   * Returns the most recent if multiple found.
   */
  private function findCaseByPic(string $pic): ?array {
    $case = \Civi\Api4\CiviCase::get(FALSE)
      ->addSelect('id', 'subject', 'start_date', 'status_id:name')
      ->addWhere('case_type_id:name', '=', self::CASE_TYPE_NAME)
      ->addWhere('EIC_Awardee_information.Organisation_PIC_number', '=', $pic)
      ->addOrderBy('start_date', 'DESC')
      ->execute()
      ->first();

    return $case ?: NULL;
  }

  /**
   * Find a VentureMatch case by client contact_id (via CaseContact).
   * Returns the most recent if multiple found.
   */
  private function findCaseByClientId(int $contactId): ?array {
    // Get case IDs where this contact is a client.
    $caseIds = \Civi\Api4\CaseContact::get(FALSE)
      ->addSelect('case_id')
      ->addWhere('contact_id', '=', $contactId)
      ->execute()
      ->column();

    if (empty($caseIds)) {
      return NULL;
    }

    // Find the most recent VentureMatch case among them.
    $case = \Civi\Api4\CiviCase::get(FALSE)
      ->addSelect('id', 'subject', 'start_date', 'status_id:name')
      ->addWhere('id', 'IN', $caseIds)
      ->addWhere('case_type_id:name', '=', self::CASE_TYPE_NAME)
      ->addOrderBy('start_date', 'DESC')
      ->execute()
      ->first();

    return $case ?: NULL;
  }

  /**
   * Create a new VentureMatch case.
   */
  private function createCase(int $contactId, ?string $startDate, ?string $statusName): array {
    $createQuery = \Civi\Api4\CiviCase::create(FALSE)
      ->addValue('case_type_id:name', self::CASE_TYPE_NAME)
      ->addValue('contact_id', $contactId)
      ->addValue('subject', 'Service Request - EIC VentureMatch (To be check)');
    if ($startDate !== NULL) {
      $createQuery->addValue('start_date', $startDate);
    }
    if ($statusName !== NULL) {
      $createQuery->addValue('status_id:name', $statusName);
    }

    return $createQuery->execute()->first();
  }

  /**
   * Process the calibration meeting activity on the case.
   *
   * Scenario A: Calibration_Date has a value
   *   → Update the most recent Meeting activity's date + status
   *   → Past/today: status = "Completed"
   *   → Future: status = "Scheduled"
   *
   * Scenario B: No Calibration_Date, but Calibration Session Sent has a value
   *   → Update the most recent Meeting activity's date + status
   *   → Status = "Ongoing"
   *
   * @return string Human-readable summary.
   */
  private function processCalibrationActivity(int $caseId, array $row): string {
    $calibrationDateRaw = $row['Calibration_Date'] ?? NULL;
    $calibrationSentRaw = $row['Calibration Session Sent'] ?? NULL;

    // Only proceed if at least one of the two fields has a value.
    if (empty($calibrationDateRaw) && empty($calibrationSentRaw)) {
      return 'Calibration: (not applicable)';
    }

    // Find the most recent Meeting activity on the case.
    $activity = $this->findLatestMeetingActivity($caseId);
    if (!$activity) {
      $this->log->warning("Case #{$caseId}: No Meeting activity found for calibration update.");
      return 'Calibration: (no Meeting activity found)';
    }

    // Determine which scenario applies.
    if (!empty($calibrationDateRaw)) {
      return $this->applyCalibrationDate($activity, $calibrationDateRaw);
    }

    return $this->applyCalibrationSent($activity, $calibrationSentRaw);
  }

  /**
   * Scenario A: Update meeting activity with Calibration_Date.
   * Past/today → "Completed", future → "Scheduled".
   */
  private function applyCalibrationDate(array $activity, string $dateRaw): string {
    $date = $this->parseDate($dateRaw);
    if ($date === NULL) {
      return 'Calibration: (invalid date)';
    }

    $isFuture = strtotime($date) > time();
    $statusName = $isFuture ? 'Scheduled' : 'Completed';

    \Civi\Api4\Activity::update(FALSE)
      ->addWhere('id', '=', $activity['id'])
      ->addValue('activity_date_time', $date)
      ->addValue('status_id:name', $statusName)
      ->execute();

    return "Calibration: Meeting #{$activity['id']} → date={$date}, status={$statusName}";
  }

  /**
   * Scenario B: Update meeting activity with Calibration Session Sent date.
   * Status → "Ongoing".
   */
  private function applyCalibrationSent(array $activity, string $dateRaw): string {
    $date = $this->parseDate($dateRaw);
    if ($date === NULL) {
      return 'Calibration: (invalid date)';
    }

    \Civi\Api4\Activity::update(FALSE)
      ->addWhere('id', '=', $activity['id'])
      ->addValue('activity_date_time', $date)
      ->addValue('status_id:name', 'Ongoing')
      ->execute();

    return "Calibration: Meeting #{$activity['id']} → date={$date}, status=Ongoing";
  }

  /**
   * Find the most recent Meeting activity on a case.
   *
   * @return array|null
   */
  private function findLatestMeetingActivity(int $caseId): ?array {
    $activity = \Civi\Api4\Activity::get(FALSE)
      ->addSelect('id', 'activity_date_time', 'status_id:name')
      ->addWhere('case_id', '=', $caseId)
      ->addWhere('activity_type_id:name', '=', 'Meeting')
      ->addOrderBy('id', 'DESC')
      ->execute()
      ->first();

    return $activity ?: NULL;
  }

  /**
   * Create a "Date First Reminder Sent" task activity if the field has a value.
   *
   * @return string Human-readable summary.
   */
  private function processFirstReminder(int $caseId, array $row): string {
    $dateRaw = $row['Date First Reminder Sent'] ?? NULL;
    if (empty($dateRaw)) {
      return 'Reminder: (not applicable)';
    }

    $date = $this->parseDate($dateRaw);
    if ($date === NULL) {
      return 'Reminder: (invalid date)';
    }

    // Find the assignee contact by the email in the IA's field.
    $iaEmail = $row["IA's"] ?? NULL;
    if (empty($iaEmail)) {
      $this->log->warning("Case #{$caseId}: No IA email found for first reminder task.");
      return 'Reminder: (no IA email)';
    }

    $assigneeId = $this->findContactByEmail($iaEmail);
    if ($assigneeId === NULL) {
      $this->log->warning("Case #{$caseId}: No contact found for IA email {$iaEmail}.");
      return "Reminder: (IA email {$iaEmail} not found)";
    }

    \Civi\Api4\Activity::create(FALSE)
      ->addValue('activity_type_id:name', 'Task')
      ->addValue('subject', 'Date First Reminder Sent')
      ->addValue('status_id:name', 'Completed')
      ->addValue('activity_date_time', $date)
      ->addValue('case_id', $caseId)
      ->addValue('source_contact_id', $assigneeId)
      ->addValue('assignee_contact_id', $assigneeId)
      ->execute();

    return "Reminder: Task created (assignee=#{$assigneeId}, date={$date})";
  }

  /**
   * Set the IA as the case role via "Investment Associate is" relationship.
   * Updates the existing role if present, creates one if not.
   *
   * @return string Human-readable summary.
   */
  private function linkIAtoCase(int $clientId, int $caseId, array $row): string {
    $iaEmail = $row["IA's"] ?? NULL;
    if (empty($iaEmail)) {
      return 'IA: (no email)';
    }

    $iaId = $this->findContactByEmail($iaEmail);
    if ($iaId === NULL) {
      $this->log->warning("No contact found for IA email {$iaEmail}.");
      return "IA: (email {$iaEmail} not found)";
    }

    // Resolve relationship type ID by machine name.
    $relTypeId = $this->findRelationshipTypeId();
    if ($relTypeId === NULL) {
      $this->log->warning("Relationship type '" . self::RELATIONSHIP_IA_NAME . "' not found.");
      return 'IA: (relationship type not found)';
    }

    // Find existing "Investment Associate is" role on this case.
    $existing = \Civi\Api4\Relationship::get(FALSE)
      ->addSelect('id', 'contact_id_a', 'contact_id_b')
      ->addWhere('case_id', '=', $caseId)
      ->addWhere('relationship_type_id', '=', $relTypeId)
      ->execute()
      ->first();

    if ($existing) {
      // Determine which side the IA is on (old direction: contact_a, new direction: contact_b).
      $iaSide = ((int) $existing['contact_id_a'] === (int) $iaId) ? 'contact_id_a' : 'contact_id_b';
      if ($iaSide === 'contact_id_a' || (int) $existing['contact_id_b'] === (int) $iaId) {
        return "IA: (already set, #{$existing['id']})";
      }
      // Update the existing role with the new IA.
      \Civi\Api4\Relationship::update(FALSE)
        ->addWhere('id', '=', $existing['id'])
        ->addValue($iaSide, $iaId)
        ->execute();
      return "IA: updated (rel #{$existing['id']})";
    }

    // No existing role — create one (client as contact_a so role reads "Investment Associate is").
    \Civi\Api4\Relationship::create(FALSE)
      ->addValue('contact_id_a', $clientId)
      ->addValue('contact_id_b', $iaId)
      ->addValue('relationship_type_id', $relTypeId)
      ->addValue('case_id', $caseId)
      ->execute();

    return "IA: created (rel on case #{$caseId})";
  }

  /**
   * Resolve the relationship type ID by its name_a_b machine name.
   *
   * @return int|null
   */
  private function findRelationshipTypeId(): ?int {
    $rt = \Civi\Api4\RelationshipType::get(FALSE)
      ->addSelect('id')
      ->addWhere('name_a_b', '=', self::RELATIONSHIP_IA_NAME)
      ->execute()
      ->first();

    return $rt ? (int) $rt['id'] : NULL;
  }

  /**
   * Find a contact ID by email address.
   *
   * @return int|null
   */
  private function findContactByEmail(?string $email): ?int {
    if (empty($email)) {
      return NULL;
    }
    $row = \Civi\Api4\Email::get(FALSE)
      ->addSelect('contact_id')
      ->addWhere('email', '=', $email)
      ->execute()
      ->first();

    return $row ? (int) $row['contact_id'] : NULL;
  }

  /**
   * Process Last_Checkin and Next_Checkin as Task activities.
   *
   * @return string Human-readable summary.
   */
  private function processCheckins(int $caseId, array $row): string {
    $checks = [
      'Last_Checkin' => 'Completed',
      'Next_Checkin' => 'Scheduled',
    ];

    $iaId = $this->findContactByEmail($row["IA's"] ?? NULL);
    $parts = [];

    foreach ($checks as $field => $status) {
      $label = str_replace('_', '', $field);
      $raw = $row[$field] ?? NULL;
      if (empty($raw)) {
        continue;
      }
      $date = $this->parseDate($raw);
      if ($date === NULL) {
        $parts[] = "{$label}: (invalid date)";
        continue;
      }
      if ($iaId === NULL) {
        $parts[] = "{$label}: (no IA)";
        continue;
      }

      \Civi\Api4\Activity::create(FALSE)
        ->addValue('activity_type_id:name', 'Task')
        ->addValue('subject', 'Checkin')
        ->addValue('status_id:name', $status)
        ->addValue('activity_date_time', $date)
        ->addValue('case_id', $caseId)
        ->addValue('source_contact_id', $iaId)
        ->addValue('assignee_contact_id', $iaId)
        ->execute();
      $parts[] = "{$label}: created ({$date})";
    }

    return $parts ? implode('; ', $parts) : 'Checkins: (none)';
  }

  /**
   * Create engagement cases for each investor listed in the row.
   *
   * @return string Human-readable summary.
   */
  private function processInvestors(int $awardeeId, string $awardeeName, array $row): string {
    $investorsRaw = $row['Investors'] ?? NULL;
    if (empty($investorsRaw)) {
      return 'Investors: (none)';
    }

    $created = [];
    $skipped = [];

    foreach (explode(',', $investorsRaw) as $investorName) {
      $investorName = trim($investorName);
      if ($investorName === '') {
        continue;
      }

      // Find the investor contact by organization name.
      $investor = \Civi\Api4\Contact::get(FALSE)
        ->addSelect('id', 'display_name')
        ->addWhere('contact_type', '=', 'Organization')
        ->addWhere('organization_name', '=', $investorName)
        ->execute()
        ->first();

      if (!$investor) {
        $skipped[] = $investorName;
        continue;
      }

      // Create an engagement case with both the investor and the awardee as clients.
      $subject = "{$investorName} - {$awardeeName}";
      $newCase = \Civi\Api4\CiviCase::create(FALSE)
        ->addValue('case_type_id:name', 'eic_engagement')
        ->addValue('contact_id', [$investor['id'], $awardeeId])
        ->addValue('subject', $subject)
        ->execute()
        ->first();

      // Fill client roles using the automation helper's classification logic.
      $this->fillEngagementRoles($newCase['id']);

      $created[] = $investorName;
    }

    $summary = 'Investors: ';
    if ($created) {
      $summary .= 'created [' . implode(', ', $created) . ']';
    }
    if ($skipped) {
      $summary .= ($created ? '; ' : '') . 'not found [' . implode(', ', $skipped) . ']';
    }
    return $summary;
  }

  /**
   * Fill engagement case client roles using the automation helper's classification logic.
   * Uses non-strict mode to avoid CLI authorization issues.
   */
  private function fillEngagementRoles(int $caseId): void {
    // Use the helper's private classification logic.
    $clients = \CRM_NcAutomations_EngagementCaseAutocompleteHelper::getInstance()->getCaseClients($caseId);

    if (empty($clients)) {
      return;
    }

    \Civi\Api4\CiviCase::update(FALSE)
      ->addWhere('id', '=', $caseId)
      ->addValue('Client_Roles.EIC_Awardee', $clients['EIC_Awardee'] ?? NULL)
      ->addValue('Client_Roles.Investor', $clients['Investor'] ?? NULL)
      ->execute();
  }

  /**
   * Parse a date string from the JSON into CiviCRM format (Y-m-d H:i:s).
   *
   * @return string|null
   */
  private function parseDate(?string $dateStr): ?string {
    if ($dateStr === NULL || $dateStr === '') {
      return NULL;
    }
    $dt = \DateTime::createFromFormat('Y-m-d\TH:i:s.u', $dateStr)
      ?: \DateTime::createFromFormat('Y-m-d\TH:i:s', $dateStr)
      ?: \DateTime::createFromFormat('Y-m-d', $dateStr);
    if ($dt === FALSE) {
      $this->log->warning("Could not parse date: {$dateStr}");
      return NULL;
    }
    return $dt->format('Y-m-d H:i:s');
  }

}
