<?php

/**
 * Collection of upgrade steps for eic_config.
 */
class CRM_EicConfig_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Enable the SES extension.
   */
  public function upgrade_1000(): bool {
    $this->ctx->log->info('Enabling SES extension');
    civicrm_api3('Extension', 'enable', ['keys' => 'ses']);
    return TRUE;
  }

  /**
   * Enable the CiviRules extension.
   */
  public function upgrade_1001(): bool {
    $this->ctx->log->info('Enabling CiviRules extension');
    civicrm_api3('Extension', 'enable', ['keys' => 'org.civicoop.civirules']);
    return TRUE;
  }

  /**
   * Enable the Contact Layout Editor extension.
   */
  public function upgrade_1002(): bool {
    $this->ctx->log->info('Enabling Contact Layout Editor extension');
    civicrm_api3('Extension', 'enable', ['keys' => 'org.civicrm.contactlayout']);
    return TRUE;
  }

  /**
   * Enable the Export Permission extension.
   */
  public function upgrade_1003(): bool {
    $this->ctx->log->info('Enabling Export Permission extension');
    civicrm_api3('Extension', 'enable', ['keys' => 'net.ourpowerbase.exportpermission']);
    return TRUE;
  }

}
