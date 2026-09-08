<?php

/**
 * Collection of upgrade steps for nc_config.
 */
class CRM_NcConfig_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Call upgrade methods in sequence.
   *
   * This function is called from the postInstall hook.
   *
   * @return bool
   *
   * @throws Exception
   */
  public static function postInstallSetup(): bool {
    // Static method - can be called from anywhere
    $upgrader = new CRM_NcConfig_Upgrader();

    $upgrader->upgrade_1001();
    $upgrader->upgrade_1002();
    $upgrader->upgrade_1003();
    $upgrader->upgrade_1004();

    // Return success.
    return TRUE;
  }

  /**
   * Renames the country with ID 1219 to 'Türkiye'.
   *
   * @return bool
   *
   * @throws Exception
   */
  public function upgrade_1001(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Rename the country with ID 1219 to \'Türkiye\'.');

    try {
      \Civi\Api4\Country::save(FALSE)->setRecords([
        [
          'id' => 1219,
          'name' => 'Türkiye',
        ],
      ])->execute();

      $log->info('Countries updated successfully');
      return TRUE;
    } catch (Exception $e) {
      $log->warning('Failed to update countries: ' . $e->getMessage());
      throw $e;
    }
  }

  /**
   * Apply default CiviCRM Configuration Settings.
   *
   * @return bool
   *
   * @throws Exception
   */
  public function upgrade_1002(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Applying default CiviCRM Configuration Settings');

    try {
      \Civi\Api4\Setting::set(FALSE)->setValues([
        'civicaseAllowMultipleClients' => TRUE,
        'defaultCurrency' => 'EUR',
        'logging' => TRUE,
        'logging_all_tables_uniquid' => 1,
      ])->execute();
      $log->info('Configuration settings applied successfully');
      return TRUE;
    } catch (Exception $e) {
      $log->warning('Configuration import failed: ' . $e->getMessage());
      throw $e;
    }
  }

  /**
   * Enable the eic_eu_survey_form_processor extension.
   */
  public function upgrade_1003(): bool {
    return $this->enable_extension(['eic_eu_survey_form_processor']);
  }

  /**
   * Enable the nc_automations extension.
   */
  public function upgrade_1004(): bool {
    return $this->enable_extension(['nc_automations']);
  }

  /**
   * Enable one or more extensions by key.
   *
   * @param array $extensions List of extension keys to enable.
   * @return bool TRUE if all extensions were enabled, FALSE if any were unavailable.
   */
  private function enable_extension(array $extensions): bool {
    $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();
    $all_enabled = TRUE;
    foreach ($extensions as $extension_name) {
      $this->ctx->log->info("Enabling {$extension_name} extension");
      if (isset($statuses[$extension_name])) {
        civicrm_api3('Extension', 'enable', ['keys' => $extension_name]);
      } else {
        $this->ctx->log->warning("{$extension_name} extension not available, skipping");
        $all_enabled = FALSE;
      }
    }
    return $all_enabled;
  }

}
