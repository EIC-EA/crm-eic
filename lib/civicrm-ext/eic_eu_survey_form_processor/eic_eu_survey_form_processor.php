<?php
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

declare(strict_types = 1);

require_once 'eic_eu_survey_form_processor.civix.php';

use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

/**
 * helper function to load classes from required Civicrm extensions
 */
function eic_eu_survey_form_processor_composer_autoload(): void {
  if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
  }
}

function load_form_processor_callback(): void {
  $fpJsonFiles = [];
  try {
    $fpJsonFiles = CRM_EicEuSurveyFormProcessor_Assets_FormProcessorAssets::getConfigFilesFullPath();
  }
  catch (\Exception $ex) {
    // @ignoreException
    \Civi::log()->error($ex->getMessage());
  }

  foreach ($fpJsonFiles as $fpConfigfile) {
    try {
      $imported = CRM_EicEuSurveyFormProcessor_Importer_FormProcessorJsonImporter::create()->import($fpConfigfile);

      if (TRUE === $imported) {
        \Civi::log()->info('Successfully imported Form-Processor: ' . $fpConfigfile);
      }
      else {
        \Civi::log()->info('Did not import Form-Processor: ' . $fpConfigfile);
      }
    }
    catch (\Exception $ex) {
      // @ignoreException
      \Civi::log()->error($ex->getMessage());
    }
  }
}

function load_civicrm_settings_callback(): void {
  $settingsJsonFiles = [];
  try {
    $settingsJsonFiles = CRM_EicEuSurveyFormProcessor_Assets_CiviSettingsAssets::getConfigFilesFullPath();
  }
  catch (\Exception $ex) {
    // @ignoreException
    \Civi::log()->error($ex->getMessage());
  }

  foreach ($settingsJsonFiles as $settingsFile) {
    try {
      $imported = CRM_EicEuSurveyFormProcessor_Importer_CiviSettingsJsonImporter::create()->import($settingsFile);

      if (TRUE === $imported) {
        \Civi::log()->info('Successfully imported CiviCRM-Settings: ' . $settingsFile);
      }
      else {
        \Civi::log()->info('Did not import CiviCRM-Settings: ' . $settingsFile);
      }
    }
    catch (\Exception $ex) {
      // @ignoreException
      \Civi::log()->error($ex->getMessage());
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function eic_eu_survey_form_processor_civicrm_config(\CRM_Core_Config &$config): void {
  eic_eu_survey_form_processor_composer_autoload();

  _eic_eu_survey_form_processor_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu/
 */

/**
 * @phpstan-ignore missingType.parameter */
function eic_eu_survey_form_processor_civicrm_navigationMenu(&$menu): void {
  _eic_eu_survey_form_processor_civix_insert_navigation_menu($menu, 'Administer/Automation', [
    'label' => 'EIC EU-Survey Form Processor Settings',
    'name' => '"eic_eu_survey_form_processor_setting_admin"',
    'url' => 'civicrm/admin/setting/eic_eu_survey_form_processor',
    'permission' => 'administer eic_eu_survey_form_processor',
    'operator' => 'OR',
    'separator' => 0,
  ]);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function eic_eu_survey_form_processor_civicrm_install(): void {
  \Civi::log()->debug('Install Extension');
  _eic_eu_survey_form_processor_civix_civicrm_install();
  load_civicrm_settings_callback();
  load_form_processor_callback();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function eic_eu_survey_form_processor_civicrm_enable(): void {
  \Civi::log()->debug('Enable Extension');
  _eic_eu_survey_form_processor_civix_civicrm_enable();
  load_civicrm_settings_callback();
  load_form_processor_callback();
}

/**
 * Implements hook_civicrm_managed().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
/** @phpstan-ignore missingType.parameter */
function eic_eu_survey_form_processor_civicrm_managed($params): void {
  \Civi::log()->debug('Load Managed Entities');
  _eic_eu_survey_form_processor_civix_civicrm_install();
}
