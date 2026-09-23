<?php

/**
 * Collection of upgrade steps for eic_config.
 */
class CRM_EicConfig_Upgrader extends CRM_Extension_Upgrader_Base {
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
  /**
   * Enable the SES extension.
   */
  public function upgrade_1000(): bool {
    return $this->enable_extension(['ses']);
  }

  /**
   * Enable the CiviRules extension.
   */
  public function upgrade_1001(): bool {
    return $this->enable_extension(['org.civicoop.civirules']);
  }

  /**
   * Enable the Contact Layout Editor extension.
   */
  public function upgrade_1002(): bool {
    return $this->enable_extension(['org.civicrm.contactlayout']);
  }

  /**
   * Enable the Export Permission extension.
   */
  public function upgrade_1003(): bool {
    return $this->enable_extension(['net.ourpowerbase.exportpermission']);
  }

  /**
   * Enable the CiviMail extension.
   */
  public function upgrade_1004(): bool {
    return $this->enable_extension(['civi_mail']);
  }

  /**
   * Enable the AIP extension.
   */
  public function upgrade_1005(): bool {
    return $this->enable_extension(['aip']);
  }

  /**
   * Enable the CiviCalendar extension.
   */
  public function upgrade_1006(): bool {
    return $this->enable_extension(['com.agiliway.civicalendar']);
  }

  /**
   * Enable XCM, advimport and advimportformprocessor extensions.
   */
  public function upgrade_1007(): bool {
    return $this->enable_extension([
      'de.systopia.xcm',
      'advimport',
      'advimportformprocessor',
    ]);
  }

  /**
   * Enable the Signatures extension.
   */
  public function upgrade_1008(): bool {
    return $this->enable_extension(['de.systopia.signatures']);
  }

  /**
   * Rename the physical DB column of the EIC Awardee representative "eulogin"
   * custom field from the copy-paste leftover `funds_vintage_year` to `eulogin`,
   * preserving any existing data.
   *
   * The custom field's machine name has always been `eulogin`; only the
   * underlying `column_name` was wrong. The managed definition
   * (0400_CustomGroup_EIC_Awardee_representative.mgd.php) now declares
   * `column_name => 'eulogin'`, but a managed reconcile does not rename an
   * existing populated column, so this upgrader performs the ALTER TABLE.
   *
   * Idempotent and defensive:
   *  - resolves the table name from CiviCRM metadata (no hardcoded assumption);
   *  - only renames when the old column exists and the new one does not;
   *  - if both exist (e.g. a prior reconcile created an empty `eulogin`),
   *    copies any data across and drops the leftover column;
   *  - does nothing when already migrated.
   *
   * Note: the identically named `funds_vintage_year` column in
   * civicrm_value_srm_financial_information belongs to a different field
   * (Investor "Funds Vintage Year") and is intentionally left untouched.
   */
  public function upgrade_1009(): bool {
    $this->ctx->log->info('Renaming eulogin column from funds_vintage_year to eulogin');

    // Resolve the actual table name for the EIC_Awardee_representative group.
    $table = CRM_Core_DAO::singleValueQuery("
      SELECT cg.table_name
      FROM civicrm_custom_group cg
      WHERE cg.name = 'EIC_Awardee_representative'
    ");

    if (empty($table)) {
      $this->ctx->log->warning('EIC_Awardee_representative custom group not found; skipping eulogin column rename.');
      return TRUE;
    }

    $hasOld = (bool) CRM_Core_DAO::singleValueQuery(
      'SHOW COLUMNS FROM `' . $table . '` LIKE %1',
      [1 => ['funds_vintage_year', 'String']]
    );
    $hasNew = (bool) CRM_Core_DAO::singleValueQuery(
      'SHOW COLUMNS FROM `' . $table . '` LIKE %1',
      [1 => ['eulogin', 'String']]
    );

    if ($hasOld && !$hasNew) {
      // Clean rename, preserving data and column definition (Text 255).
      CRM_Core_DAO::executeQuery(
        'ALTER TABLE `' . $table . '` CHANGE COLUMN `funds_vintage_year` `eulogin` VARCHAR(255) NULL DEFAULT NULL'
      );
      $this->ctx->log->info("Renamed {$table}.funds_vintage_year to eulogin.");
    }
    elseif ($hasOld && $hasNew) {
      // A prior reconcile already created an empty `eulogin`. Preserve any data
      // then drop the leftover column.
      CRM_Core_DAO::executeQuery(
        'UPDATE `' . $table . '` SET `eulogin` = `funds_vintage_year` WHERE `eulogin` IS NULL OR `eulogin` = %1',
        [1 => ['', 'String']]
      );
      CRM_Core_DAO::executeQuery('ALTER TABLE `' . $table . '` DROP COLUMN `funds_vintage_year`');
      $this->ctx->log->info("Merged {$table}.funds_vintage_year into eulogin and dropped the leftover column.");
    }
    else {
      // Already migrated (only `eulogin` exists) or neither column present.
      $this->ctx->log->info("No rename needed for {$table} (eulogin column already correct).");
    }

    return TRUE;
  }

}