<?php

use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

/**
 * Collection of upgrade steps for eic_eu_survey_form_processor.
 */
class CRM_EicEuSurveyFormProcessor_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Enable the eic_fp_action_provider extension.
   */
  public function upgrade_1001(): bool {
    return $this->enable_extension(['eic_fp_action_provider']);
  }

  /**
   * Update EIC Investor Onboarding from EU Survey Form Processor.
   *
   * @return bool
   *
   * @throws Exception
   */
  public function upgrade_1002(): bool {
    // Get the logger.
    $log = $this->ctx->log ?? \Civi::log();

    // Status message.
    $log->info('Update EIC Investor Onboarding from EU Survey Form Processor');

    // Get the configuration file.
    $file = E::path(CRM_EicEuSurveyFormProcessor_Assets_FormProcessorAssets::ASSETS_DIR . "/eic_investor_onboarding_from_eu_survey.json");
    if (!file_exists($file)) {
      // Throw exception if file not found.
      $log->warning('Form Processor for EU Survey Investors config file not found: ' . $file);
      throw new Exception("Form Processor for EU Survey Investors config file not found: $file");
    }

    try {
      // Import settings.
      $result = civicrm_api3('FormProcessorInstance', 'import', [
        'file' => $file,
      ]);

      // Log success.
      $log->info('FormProcessorInstance configuration settings imported successfully');

      // Return success.
      return TRUE;
    } catch (Exception $e) {
      // Log failure.
      $log->warning('FormProcessorInstance import failed: ' . $e->getMessage());

      // Throw exception.
      throw $e;
    }
  }

  /**
   * Enable one or more extensions by key.
   *
   * @param array $extensions List of extension keys to enable.
   * @return bool TRUE if all extensions were enabled, FALSE if any were unavailable.
   */
  private function enable_extension(array $extensions): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();
    $all_enabled = TRUE;
    foreach ($extensions as $extension_name) {
      $log->info("Enabling {$extension_name} extension");
      if (isset($statuses[$extension_name])) {
        civicrm_api3('Extension', 'enable', ['keys' => $extension_name]);
      } else {
        $log->warning("{$extension_name} extension not available, skipping");
        $all_enabled = FALSE;
      }
    }
    return $all_enabled;
  }

}
