<?php
declare(strict_types = 1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'eic_fp_action_provider.civix.php';
// phpcs:enable

use CRM_EicFpActionProvider_ExtensionUtil as E;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function eic_fp_action_provider_civicrm_config(\CRM_Core_Config $config): void {
  _eic_fp_action_provider_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_container().
 *
 * Registers the form-processor conditions with the action provider.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_container/
 */
function eic_fp_action_provider_civicrm_container(ContainerBuilder $container): void {
  if (class_exists('Civi\EicFpActionProvider\ContainerSpecs')) {
    $container->addCompilerPass(new \Civi\EicFpActionProvider\ContainerSpecs());
  }
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function eic_fp_action_provider_civicrm_install(): void {
  _eic_fp_action_provider_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function eic_fp_action_provider_civicrm_enable(): void {
  _eic_fp_action_provider_civix_civicrm_enable();
}
