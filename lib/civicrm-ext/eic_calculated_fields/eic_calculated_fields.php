<?php

declare(strict_types=1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'eic_calculated_fields.civix.php';
// phpcs:enable

use Civi\EicCalculatedFields\ReadinessScore;
use CRM_EicCalculatedFields_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 */
function eic_calculated_fields_civicrm_config(\CRM_Core_Config $config): void {
  _eic_calculated_fields_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 */
function eic_calculated_fields_civicrm_install(): void {
  _eic_calculated_fields_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 */
function eic_calculated_fields_civicrm_enable(): void {
  _eic_calculated_fields_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_custom().
 *
 * Fires after custom field values are written for an entity. We use it as the
 * "entity saved" trigger for the VentureMatch scoring fields: when any value in
 * the VentureMatch_Fields group is saved for an organisation, recompute the
 * Readiness Score as the sum of its component fields (unset = 0).
 *
 * CiviCRM calls this hook once per custom group written in a given save, so for
 * the VentureMatch group it runs a single time per save.
 *
 * @param string $op
 *   The operation: 'create', 'edit' or 'delete'.
 * @param int $groupID
 *   The custom group being written.
 * @param int $entityID
 *   The entity (here: organisation contact) ID.
 * @param array $params
 *   The custom field values being written. Not relied upon: the helper reads
 *   the persisted values back via API4 so single-field edits still total
 *   correctly.
 */
function eic_calculated_fields_civicrm_custom($op, $groupID, $entityID, &$params): void {
  // Only react to writes (create/edit).
  if (!in_array($op, ['create', 'edit'], TRUE)) {
    return;
  }

  if (empty($entityID) || empty($groupID)) {
    return;
  }

  // Only react when the VentureMatch group is the one being saved. The group
  // id lookup is cached, so this is cheap on every custom-data save.
  if ((int) $groupID !== ReadinessScore::ventureMatchGroupId()) {
    return;
  }

  // Guard against the recursive write-back the helper performs on the same
  // custom group.
  static $running = FALSE;
  if ($running) {
    return;
  }
  $running = TRUE;
  try {
    ReadinessScore::recalculateForContact((int) $entityID);
  }
  catch (\Throwable $e) {
    \Civi::log()->error('eic_calculated_fields: readiness score recalculation failed for contact {cid}: {msg}', [
      'cid' => $entityID,
      'msg' => $e->getMessage(),
    ]);
  }
  finally {
    $running = FALSE;
  }
}
