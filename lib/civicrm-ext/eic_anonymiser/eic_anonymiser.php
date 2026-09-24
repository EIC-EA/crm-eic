<?php
declare(strict_types=1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'eic_anonymiser.civix.php';
// phpcs:enable

use CRM_EicAnonymiser_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 */
function eic_anonymiser_civicrm_config(\CRM_Core_Config $config): void {
  _eic_anonymiser_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 */
function eic_anonymiser_civicrm_install(): void {
  _eic_anonymiser_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 */
function eic_anonymiser_civicrm_enable(): void {
  _eic_anonymiser_civix_civicrm_enable();
}
