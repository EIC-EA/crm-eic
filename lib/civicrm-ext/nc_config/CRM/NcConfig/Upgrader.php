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
    $upgrader->upgrade_1005();
    $upgrader->upgrade_1006();

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
    $log->info('Start nc_config/upgrade_1001 - Rename the country with ID 1219 to \'Türkiye\'.');

    try {
      \Civi\Api4\Country::save(FALSE)->setRecords([
        [
          'id' => 1219,
          'name' => 'Türkiye',
        ],
      ])->execute();

      $log->info('Countries updated successfully');
    } catch (Exception $e) {
      $log->warning('Failed to update countries: ' . $e->getMessage());
      throw $e;
    }
    $log->info('Finished nc_config/upgrade_1001 - Rename the country with ID 1219 to \'Türkiye\'.');
    return TRUE;
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
    $log->info('Start nc_config/upgrade_1002 - Applying default CiviCRM Configuration Settings (currency, logging).');

    try {
      \Civi\Api4\Setting::set(FALSE)->setValues([
        'civicaseAllowMultipleClients' => TRUE,
        'defaultCurrency' => 'EUR',
        'logging' => TRUE,
        'logging_all_tables_uniquid' => 1,
      ])->execute();
      $log->info('Configuration settings applied successfully');

    } catch (Exception $e) {
      $log->warning('Configuration import failed: ' . $e->getMessage());
      throw $e;
    }
    $log->info('Finished nc_config/upgrade_1002 - Applying default CiviCRM Configuration Settings (currency, logging).');
    return TRUE;
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
   * Delete stale custom groups and fields.
   *
   * @return bool
   */
  public function upgrade_1005(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Start nc_config/upgrade_1005 - Clean stale managed entities (groups/fields) for custom group "CustomGroup_EU_Survey_Investor_Onboarding".');
    $orphan = 'civicrm_value_srm_eu_survey_investor_onboarding_case';

    foreach ([$orphan, "log_{$orphan}"] as $table) {
      // Drop the custom group database table.
      $exists = CRM_Core_DAO::singleValueQuery(
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = %1",
        [1 => [$table, 'String']]
      );
      if ($exists) {
        $log->info("Dropping orphaned table {$table}");
        CRM_Core_DAO::executeQuery("DROP TABLE `{$table}`");
      }
    }

    $stale = [
      'CustomGroup_EU_Survey_Investor_Onboarding',
      'CustomGroup_EU_Survey_Investor_Onboarding_CustomField_Organisation_name',
      'CustomGroup_EU_Survey_Investor_Onboarding_CustomField_Organisation_domain',
    ];
    foreach ($stale as $name) {
      // Select the entityType of the ManagedEntity.
      $dao = CRM_Core_DAO::executeQuery(
        "SELECT entity_type, entity_id FROM civicrm_managed WHERE module = 'nc_config' AND name = %1",
        [1 => [$name, 'String']]
      );

      // If nothing is returned, there is no record in the civicrm_managed table.
      if (!$dao->fetch()) {
        $log->info("No civicrm_managed entry found for: {$name}");
        continue;
      }

      // Get api4 delete commands.
      $delete = match ($dao->entity_type) {
        'CustomGroup' => \Civi\Api4\CustomGroup::delete(FALSE),
        'CustomField' => \Civi\Api4\CustomField::delete(FALSE),
        default => NULL,
      };

      if ($exists) {
        $log->warning("Entity still exists ({$dao->entity_type}.id={$dao->entity_id}), skipping: {$name}");
        continue;
      }

      // Execute delete.
      $delete->addWhere('id', '=', $dao->entity_id)->execute();

      // Delete entries from civicrm_managed table.
      CRM_Core_DAO::executeQuery(
        "DELETE FROM civicrm_managed WHERE module = 'nc_config' AND name = %1",
        [1 => [$name, 'String']]
      );
    }
    $log->info('Finished nc_config/upgrade_1005 - Clean stale managed entities (groups/fields) for custom group "CustomGroup_EU_Survey_Investor_Onboarding".');
    return TRUE;
  }

  /**
   * Update OptionValue with name: Closed.
   */
  public function upgrade_1006(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Start nc_config/upgrade_1006 - Update OptionValue with name: Closed.');
    \Civi\Api4\OptionValue::update(TRUE)
      ->addValue('label', 'Resolved')
      ->addWhere('option_group_id:name', '=', 'activity_status')
      ->addWhere('name', '=', 'Closed')
      ->execute();
    $log->info('Finished nc_config/upgrade_1006 - Update OptionValue with name: Closed.');
    return TRUE;
  }

  /**
   * Update RelationshipType with name_a_b: Founder of.
   */
  public function upgrade_1007(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Start nc_config/upgrade_1007 - Update RelationshipType with name_a_b: Founder of.');
    \Civi\Api4\RelationshipType::update(TRUE)
      ->addValue('name_a_b', 'Founder of')
      ->addValue('label_a_b', 'Founder of')
      ->addValue('description', "The founder of the organisation. Communicated by the beneficiary in the EIC VM Fundraising Assessment EU-Survey (Founder's Primary Contact), and used as a role on the VentureMatch Service Request case.")
      ->addValue('contact_type_a', 'Individual')
      ->addValue('contact_type_b', 'Organization')
      ->addWhere('name_b_a', '=', 'Founder is')
      ->addWhere('label_b_a', '=', 'Founder is')
      ->execute();
    $log->info('Finished nc_config/upgrade_1007 - Update RelationshipType with name_a_b: Founder of.');
    return TRUE;
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

  /**
   * Disable one or more extensions by key.
   *
   * @param array $extensions List of extension keys to enable.
   * @return bool TRUE if all extensions were enabled, FALSE if any were unavailable.
   */
  private function disable_extension(array $extensions): bool {
    $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();
    $all_disabled = TRUE;
    foreach ($extensions as $extension_name) {
      $this->ctx->log->info("Disabling {$extension_name} extension");
      if (isset($statuses[$extension_name])) {
        civicrm_api3('Extension', 'disable', ['keys' => $extension_name]);
      } else {
        $this->ctx->log->warning("{$extension_name} extension not available, skipping");
        $all_enabled = FALSE;
      }
    }
    return $all_disabled;
  }

}
