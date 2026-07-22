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
  $requiredComponents = ['CiviMail'];

  $currentComponents = \Civi::settings()->get('enable_components') ?? [];
  $missing = array_diff($requiredComponents, $currentComponents);

  if (!empty($missing)) {
    $updated = array_unique(array_merge($currentComponents, $missing));
    \Civi::settings()->set('enable_components', array_values($updated));
  }
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
