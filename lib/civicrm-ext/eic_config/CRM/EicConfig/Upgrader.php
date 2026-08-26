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

}