<?php
declare(strict_types = 1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'eic_config.civix.php';
// phpcs:enable

use CRM_EicConfig_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function eic_config_civicrm_config(\CRM_Core_Config $config): void {
  _eic_config_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function eic_config_civicrm_install(): void {
  _eic_config_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * Ensures required extensions are enabled when eic_config is enabled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function eic_config_civicrm_enable(): void {
  _eic_config_civix_civicrm_enable();
  _eic_config_ensure_dependencies_enabled();
  _eic_config_ensure_components();
}

/**
 * Checks if required extensions are enabled, and enables them if not.
 */
function _eic_config_ensure_dependencies_enabled(): void {
  $requiredExtensions = [
    'aip',
    'ses',
    'org.civicoop.civirules',
    'org.civicrm.contactlayout',
    'net.ourpowerbase.exportpermission',
  ];

  $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();

  $toEnable = [];
  foreach ($requiredExtensions as $key) {
    if (!isset($statuses[$key]) || $statuses[$key] !== \CRM_Extension_Manager::STATUS_INSTALLED) {
      $toEnable[] = $key;
    }
  }

  if (!empty($toEnable)) {
    civicrm_api3('Extension', 'enable', ['keys' => $toEnable]);
  }
}

/**
 * Ensures required CiviCRM components are enabled.
 */
function _eic_config_ensure_components(): void {
  civicrm_api3('Extension', 'enable', ['keys' => 'civi_mail']);
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * Sets up group hierarchy (parent/child relationships) that cannot be
 * expressed via implicit joins in .mgd.php files because the `parents`
 * field is a serialized array of IDs, not a proper FK.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function eic_config_civicrm_postInstall(): void {
  _eic_config_set_group_hierarchy();
  _eic_config_ensure_profile_ufjoins();
}

/**
 * Registers the profiles for Contact Summary use (UFJoin), both modules.
 *
 * The profiles (UFGroup/UFField) and the contact layouts (ContactLayout) are
 * shipped as managed entities, but the UFJoin rows that wire a profile into the
 * Contact Summary cannot be expressed as managed entities (UFJoin is not an
 * API4/managed-capable entity). Two distinct UFJoin rows are required per
 * profile, each consumed by a different code path:
 *
 *  - module = 'Contact Summary': the Contact Layout editor builds its list of
 *    available profile blocks from these rows (contactlayout BAO
 *    getAllBlocks()). Without it the profile.* blocks are never offered.
 *
 *  - module = 'Profile': the inline edit/view endpoint
 *    (CRM_Profile_Form_Edit::preProcess, civicrm/ajax/inline) validates the
 *    profile is "of type Profile". Without it that endpoint statusBounces and
 *    the browser gets a 302 to civicrm/contact/view instead of the JSON
 *    snippet, so the block fails to render inline.
 *
 * A profile needs BOTH rows; dropping either breaks one half.
 *
 * This runs at postInstall because civix sets the extension's schema_version to
 * the latest on a fresh install, so the historical upgrade_1015() step does not
 * run for new installs. upgrade_1015() still covers already-installed
 * environments that upgrade in place. Both are idempotent.
 */
function _eic_config_ensure_profile_ufjoins(): void {
  // UFGroup.name values referenced as profile.* blocks in the ContactLayout
  // managed entities (3000/3020).
  $profiles = [
    'VentureMatch_EIC_Awardee_information',
    'Custom_EIC_Awardee_Organisation_information_right',
    'EIC_VentureMatch_Main_information_Left',
    'EIC_VentureMatch_Fundraising_Snapshot',
    'EIC_VentureMatch_Fundraising_Documents',
  ];

  // Each profile needs a UFJoin for both modules (see docblock).
  $modules = ['Contact Summary', 'Profile'];

  foreach ($profiles as $name) {
    $ufGroupId = CRM_Core_DAO::singleValueQuery(
      'SELECT id FROM civicrm_uf_group WHERE name = %1',
      [1 => [$name, 'String']]
    );

    if (empty($ufGroupId)) {
      \Civi::log()->warning("eic_config: UFGroup '{$name}' not found; skipping Contact Summary UFJoins.");
      continue;
    }

    foreach ($modules as $module) {
      $exists = (bool) CRM_Core_DAO::singleValueQuery(
        'SELECT id FROM civicrm_uf_join WHERE uf_group_id = %1 AND module = %2',
        [1 => [$ufGroupId, 'Integer'], 2 => [$module, 'String']]
      );

      if ($exists) {
        continue;
      }

      civicrm_api3('UFJoin', 'create', [
        'module' => $module,
        'uf_group_id' => $ufGroupId,
        'is_active' => 1,
        'weight' => 1,
      ]);
    }
  }
}

/**
 * Defines and applies the group hierarchy.
 *
 * Each entry maps a child group name to an array of parent group names.
 * Groups are resolved by their `name` field (machine name), making this
 * portable across environments regardless of numeric IDs.
 */
function _eic_config_set_group_hierarchy(): void {
  // Define hierarchy: child_group_name => [parent_group_name, ...]
  $hierarchy = [
    'EIC_Community_Programme' => ['EIC_BAS_Programme'],
    'EIC_VentureMatch' => ['EIC_BAS_Programme'],
  ];

  // Collect all group names we need to resolve.
  $allNames = [];
  foreach ($hierarchy as $child => $parents) {
    $allNames[] = $child;
    $allNames = array_merge($allNames, $parents);
  }
  $allNames = array_unique($allNames);

  // Fetch IDs for all referenced groups in a single query.
  $groups = \Civi\Api4\Group::get(FALSE)
    ->addSelect('id', 'name')
    ->addWhere('name', 'IN', $allNames)
    ->execute()
    ->indexBy('name');

  // Apply parent assignments.
  foreach ($hierarchy as $childName => $parentNames) {
    if (empty($groups[$childName])) {
      \Civi::log()->warning("eic_config: Cannot set group hierarchy — child group '{$childName}' not found.");
      continue;
    }

    $parentIds = [];
    foreach ($parentNames as $parentName) {
      if (empty($groups[$parentName])) {
        \Civi::log()->warning("eic_config: Cannot set group hierarchy — parent group '{$parentName}' not found.");
        continue;
      }
      $parentIds[] = $groups[$parentName]['id'];
    }

    if (!empty($parentIds)) {
      \Civi\Api4\Group::update(FALSE)
        ->addWhere('id', '=', $groups[$childName]['id'])
        ->addValue('parents', $parentIds)
        ->execute();
    }
  }
}

