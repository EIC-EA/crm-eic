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

  /**
   * Enable the CiviMail component.
   */
  public function upgrade_1004(): bool {
    $this->ctx->log->info('Enabling CiviMail component');
    $components = \Civi::settings()->get('enable_components') ?? [];
    if (!in_array('CiviMail', $components)) {
      $components[] = 'CiviMail';
      \Civi::settings()->set('enable_components', $components);
    }
    return TRUE;
  }

  /**
   * Enable the Civi Calendar extension.
   */
  public function upgrade_1005(): bool {
    $this->ctx->log->info('Enabling CiviCalendar extension');
    civicrm_api3('Extension', 'enable', ['keys' => 'com.agiliway.civicalendar']);
    return TRUE;
  }
}
