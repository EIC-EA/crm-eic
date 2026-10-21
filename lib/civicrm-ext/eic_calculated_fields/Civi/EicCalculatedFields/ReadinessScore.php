<?php

declare(strict_types=1);

namespace Civi\EicCalculatedFields;

use Civi\Api4\Contact;
use Civi\Api4\CustomGroup;

/**
 * Computes and persists the VentureMatch "Readiness Score" for an organisation.
 *
 * Readiness Score = Pitch Deck + C-Level/Team + Data Room + Financial Model.
 * Any component that is unset / NULL is treated as 0.
 *
 * All fields live in the "VentureMatch_Fields" custom group (provided by the
 * eic_config extension), which extends Organization contacts. Reads and writes
 * go through API4 so CiviCRM's field abstraction, permissions bypass and hooks
 * behave consistently.
 */
class ReadinessScore {

  /**
   * Custom group holding the VentureMatch scoring fields.
   */
  public const CUSTOM_GROUP = 'VentureMatch_Fields';

  /**
   * Field that receives the calculated total.
   */
  public const TARGET_FIELD = 'Readiness_Score';

  /**
   * Component fields that are summed into the target field.
   *
   * @var string[]
   */
  public const COMPONENT_FIELDS = [
    'Pitch_Deck2',
    'C_Level_Team2',
    'Data_Room2',
    'Financial_Model2',
  ];

  /**
   * Cached custom group id for VentureMatch_Fields (0 = resolved-but-absent).
   */
  private static ?int $groupId = NULL;

  /**
   * Recalculate and persist the Readiness Score for a single contact.
   *
   * Unset components are treated as 0, so a score is always written.
   *
   * @param int $contactId
   *   The organisation contact ID.
   */
  public static function recalculateForContact(int $contactId): void {
    if ($contactId <= 0) {
      return;
    }

    // Read the full, persisted record. We read every component (not just the
    // ones in the hook's $params) so an inline edit of a single field still
    // produces a correct total.
    $values = self::readValues($contactId);
    if ($values === NULL) {
      return;
    }

    $total = 0;
    foreach (self::COMPONENT_FIELDS as $field) {
      // NULL / missing / non-numeric components count as 0.
      $total += (int) ($values[self::prefixed($field)] ?? 0);
    }

    $current = $values[self::prefixed(self::TARGET_FIELD)] ?? NULL;
    if ($current !== NULL && (int) $current === $total) {
      // Already correct; skip the write (and the recursive hook it triggers).
      return;
    }

    self::writeScore($contactId, $total);
  }

  /**
   * Read the component and target values for a contact via API4.
   *
   * @param int $contactId
   *
   * @return array<string, mixed>|null
   *   API4 result row keyed by "VentureMatch_Fields.<Field>", or NULL if the
   *   contact / group could not be read.
   */
  private static function readValues(int $contactId): ?array {
    $select = [];
    foreach (array_merge(self::COMPONENT_FIELDS, [self::TARGET_FIELD]) as $field) {
      $select[] = self::prefixed($field);
    }

    try {
      $row = Contact::get(FALSE)
        ->setSelect($select)
        ->addWhere('id', '=', $contactId)
        ->execute()
        ->first();
    }
    catch (\Throwable $e) {
      \Civi::log()->warning('eic_calculated_fields: unable to read VentureMatch values for contact {cid}: {msg}', [
        'cid' => $contactId,
        'msg' => $e->getMessage(),
      ]);
      return NULL;
    }

    return $row ?: NULL;
  }

  /**
   * Persist the computed score to the target custom field via API4.
   *
   * The target field is flagged is_view (read-only in the form UI); API4 can
   * still write it. Recursion back into this helper is prevented by the
   * reentrancy guard in the hook_civicrm_custom implementation.
   *
   * @param int $contactId
   * @param int $total
   */
  private static function writeScore(int $contactId, int $total): void {
    Contact::update(FALSE)
      ->addWhere('id', '=', $contactId)
      ->addValue(self::prefixed(self::TARGET_FIELD), $total)
      ->execute();
  }

  /**
   * Resolve (and cache) the custom group id for VentureMatch_Fields.
   *
   * @return int
   *   The group id, or 0 if the group is not deployed on this site.
   */
  public static function ventureMatchGroupId(): int {
    if (self::$groupId !== NULL) {
      return self::$groupId;
    }
    try {
      $id = CustomGroup::get(FALSE)
        ->addSelect('id')
        ->addWhere('name', '=', self::CUSTOM_GROUP)
        ->execute()
        ->first()['id'] ?? 0;
      self::$groupId = (int) $id;
    }
    catch (\Throwable $e) {
      self::$groupId = 0;
    }
    return self::$groupId;
  }

  /**
   * Build the API4 "CustomGroup.Field" selector for a field name.
   */
  private static function prefixed(string $field): string {
    return self::CUSTOM_GROUP . '.' . $field;
  }

  /**
   * Clear the static cache (useful in tests / after schema changes).
   */
  public static function flushCache(): void {
    self::$groupId = NULL;
  }

}
