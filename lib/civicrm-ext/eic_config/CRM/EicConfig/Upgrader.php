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
   * Enable the CiviMail extension.
   */
  public function upgrade_1004(): bool {
    $this->ctx->log->info('Enabling CiviMail extension');
    civicrm_api3('Extension', 'enable', ['keys' => 'civi_mail']);
    return TRUE;
  }

  /**
   * Enable the AIP extension.
   */
  public function upgrade_1005(): bool {
    $this->ctx->log->info('Enabling AIP extension');
    civicrm_api3('Extension', 'enable', ['keys' => 'aip']);
    return TRUE;
  }

  /**
   * Enable the Civi Calendar extension.
   */
  public function upgrade_1006(): bool {
    $this->ctx->log->info('Enabling CiviCalendar extension');
    $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();
    if (isset($statuses['com.agiliway.civicalendar'])) {
      civicrm_api3('Extension', 'enable', ['keys' => 'com.agiliway.civicalendar']);
    } else {
      $this->ctx->log->warning('CiviCalendar extension not available, skipping');
    }
    return TRUE;
  }

  public function upgrade_1007(): bool {
    $this->ctx->log->info('Enabling de.systopia.xcm extension');
    $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();
    if (isset($statuses['de.systopia.xcm'])) {
      civicrm_api3('Extension', 'enable', ['keys' => 'de.systopia.xcm']);
    } else {
      $this->ctx->log->warning('de.systopia.xcm extension not available, skipping');
    }
    $this->ctx->log->info('Enabling advimport extension');
    $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();
    if (isset($statuses['advimport'])) {
      civicrm_api3('Extension', 'enable', ['keys' => 'advimport']);
    } else {
      $this->ctx->log->warning('advimport extension not available, skipping');
    }
    $this->ctx->log->info('Enabling adv import form processor extension');
    $statuses = \CRM_Extension_System::singleton()->getManager()->getStatuses();
    if (isset($statuses['advimportformprocessor'])) {
      civicrm_api3('Extension', 'enable', ['keys' => 'advimportformprocessor']);
    } else {
      $this->ctx->log->warning('adv import form processor extension not available, skipping');
    }
    return TRUE;
  }
}