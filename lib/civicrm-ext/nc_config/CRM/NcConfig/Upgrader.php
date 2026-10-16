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
    $upgrader->upgrade_1007();
    $upgrader->upgrade_1008();
    $upgrader->upgrade_1009();
    $upgrader->upgrade_1010();

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

      // Delete entries from civicrm_managed table.
      CRM_Core_DAO::executeQuery(
        "DELETE FROM civicrm_managed WHERE module = 'nc_config' AND name = %1",
        [1 => [$name, 'String']]
      );

      $log->info("Deleted managed entity: {$name}");

      // Get api4 delete commands.
      match ($dao->entity_type) {
        'CustomGroup' => \Civi\Api4\CustomGroup::delete(FALSE)->addWhere('id', '=', $dao->entity_id)->execute(),
        'CustomField' => \Civi\Api4\CustomField::delete(FALSE)->addWhere('id', '=', $dao->entity_id)->execute(),
        default => NULL,
      };
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
    \Civi\Api4\OptionValue::update(FALSE)
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
    \Civi\Api4\RelationshipType::update(FALSE)
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
   * Update OptionValue with name: n/a.
   */
  public function upgrade_1008(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Start nc_config/upgrade_1008 - Update OptionValue with name: n/a.');
    $results = \Civi\Api4\OptionValue::update(FALSE)
      ->addValue('name', 'n_a')
      ->addWhere('option_group_id:name', '=', 'EIC_Horizon_Europe_Project_Funding_Type_2')
      ->addWhere('value', '=', 'n/a')
      ->execute();
    $log->info('Finished nc_config/upgrade_1008 - Update OptionValue with name: n/a.');
    return TRUE;
  }

  /**
   * Update civicrm_value_srm_eic_accelerator_onboarding_survey_data column names.
   */
  public function upgrade_1009(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Start nc_config/upgrade_1009 - Update civicrm_value_srm_eic_accelerator_onboarding_survey_data column names..');

    // PROD COL NAMES.
    $columnMapping = [
      'eic_project_acronym_94' => 'eic_project_acronym',
      'eic_project_id_95' => 'eic_project_id',
      'pitch_deck_96' => 'case_latest_pitch_deck',
      'are_you_fundraising_within_the_n_97' => 'survey_fundraising_within_the_next_18_months',
      'are_you_fundraising_within__98' => 'survey_fundraising_within',
      'what_fundraising_support_do_you__99' => 'survey_receive_suport_fundraising_items',
      'would_you_like_to_receive_invest_100' => 'survey_receive_support_investment',
      'are_you_actively_seeking_corpora_101' => 'survey_partenrs_actively_seeking',
      'what_type_of_partner_are_you_tar_102' => 'survey_partners_types',
      'are_you_planning_to_sell_your_in_103' => 'survey_selling_to_public_private_buyers',
      'survey_buyers_where_in_process' => 'survey_buyers_where_in_process',
      'would_you_like_procurement_suppo_105' => 'survey_receive_support_procurement',
      'are_you_planning_to_expand_inter_106' => 'survey_international_expansion_in_the_next_18_months',
      'which_markets_107' => 'survey_international_expansion_markets',
      'what_is_your_main_barrier_108' => 'survey_international_expansion_main_barrier',
      'survey_trade_fairs_next_12_months' => 'survey_trade_fairs_next_12_months',
      'which_region_110' => 'survey_international_trade_fairs_region',
      'type_of_events_111' => 'survey_international_trade_fairs_events',
      'would_you_benefit_from_industry__112' => 'survey_receive_support_industry_experts_or_coaches',
      'what_type_of_support_113' => 'survey_receive_support_industry_experts_or_coaches_types',
      'what_is_your_main_challenge__114' => 'survey_main_challenge',
      'does_your_company_have_a_woman_f_115' => 'survey_Does_your_company_have_a_woman_founder_or_executive_who',
      'what_type_of_other_support_are_y_116' => 'survey_receive_support_other_types',
      'are_there_any_other_support_item_117' => 'survey_receive_support_other_items',
      'general_feedback_118' => 'survey_feedback',
      'would_you_like_to_receive_suppor_119' => 'survey_receive_support_entering_new_markets',
    ];

    foreach ($columnMapping as $oldColName => $newColName) {
      $table = 'civicrm_value_srm_eic_accelerator_onboarding_survey_data';
      CRM_Core_DAO::executeQuery("ALTER TABLE `$table` RENAME COLUMN `$oldColName` TO `$newColName`");
    }

    $log->info('Finished nc_config/upgrade_1009 - Update civicrm_value_srm_eic_accelerator_onboarding_survey_data column names.');
    return TRUE;
  }

  /**
   * Update civicrm_value_srm_eu_survey_company_data column names.
   */
  public function upgrade_1010(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Start nc_config/upgrade_1010 - Update civicrm_value_srm_eu_survey_company_data column names.');

    // PROD COL NAMES.
    $columnMapping = [
      'ceo_or_project_leader_gender_153' => 'ceo_or_project_leader_gender',
      'founder_gender_154' => 'founder_gender',
      'crl_155' => 'crl',
      'brl_156' => 'brl',
      'frl_157' => 'frl',
    ];

    foreach ($columnMapping as $oldColName => $newColName) {
      $table = 'civicrm_value_srm_eu_survey_company_data';
      CRM_Core_DAO::executeQuery("ALTER TABLE `$table` RENAME COLUMN `$oldColName` TO `$newColName`");
    }

    $log->info('Finished nc_config/upgrade_1010 - Update civicrm_value_srm_eu_survey_company_data column names.');
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
