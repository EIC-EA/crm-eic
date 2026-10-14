<?php
declare(strict_types = 1);

use CRM_NcAutomations_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_NcAutomations_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Clean up managed entities left stuck by a previous installation and then
   * reinstall the managed entities of the civirules and nc_automations modules.
   *
   * @return TRUE on success
   * @throws \CRM_Core_Exception
   */
  public function upgrade_1001(): bool {
    $this->ctx->log->info('Applying update 1001: repair civirules managed entities');

    // Remove nc_automations managed entities stuck from a previous installation.
    $managed = \Civi\Api4\Managed::get()
      ->addWhere('module', '=', 'nc_automations')
      ->execute();
    foreach ($managed as $record) {
      \Civi\Api4\Managed::delete()
        ->addWhere('id', '=', $record['id'])
        ->execute();
    }

    // Reinstall (reconcile) civirules managed entities.
    \Civi\Api4\Managed::reconcile(FALSE)
      ->addModule('org.civicoop.civirules')
      ->execute();

    // Reinstall (reconcile) nc_automations managed entities.
    \Civi\Api4\Managed::reconcile(FALSE)
      ->addModule('nc_automations')
      ->execute();

    return TRUE;
  }

}
