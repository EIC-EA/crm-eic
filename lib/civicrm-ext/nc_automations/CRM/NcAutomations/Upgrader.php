<?php

/**
 * Collection of upgrade steps for nc_config.
 */
class CRM_NcAutomations_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Call upgrade methods in sequence.
   *
   * This function is called from the postInstall hook.
   *
   * @return bool
   *
   * @throws Exception
   */
  public static function callOnUninstall(): bool {
    // Static method - can be called from anywhere
    $upgrader = new CRM_NcAutomations_Upgrader();

    $upgrader->upgrade_1001();

    // Return success.
    return TRUE;
  }

  /**
   * Clears all custom CiviRules, Rule Actions/Conditions and Managed entities for nc_automations.
   *
   * @return bool
   *
   * @throws Exception
   */
  public function upgrade_1001(): bool {
    $log = $this->ctx->log ?? \Civi::log();
    $log->info('Applying update 1001: repair CiviRules managed entities.');


    // 0. Pull the ids nc_automations actually manages, grouped by type.
    $ids = [];
    foreach (\Civi\Api4\Managed::get(FALSE)
     ->addSelect('entity_type', 'entity_id')
     ->addWhere('module', '=', 'nc_automations')
     ->addWhere('entity_id', 'IS NOT NULL')
     ->execute() as $row) {
      $ids[$row['entity_type']][] = $row['entity_id'];
    }

    $ruleIds = $ids['CiviRulesRule'] ?? [];
    $actionIds = $ids['CiviRulesAction'] ?? [];
    $triggerIds = $ids['CiviRulesTrigger'] ?? [];

    // 1. Rules + their junctions, per rule (your get+chains pattern).
    // Junctions go first, then the rule. Runs once per rule, each targeting its own $id.
    if ($ruleIds) {
      \Civi\Api4\CiviRulesRule::get(FALSE)
        ->addWhere('id', 'IN', $ruleIds)
        ->addChain('deleteRuleRuleActions', \Civi\Api4\CiviRulesRuleAction::delete(FALSE)->addWhere('rule_id', '=', '$id'))
        ->addChain('deleteRuleRuleConditions', \Civi\Api4\CiviRulesRuleCondition::delete(FALSE)->addWhere('rule_id', '=', '$id'))
        ->addChain('deleteRule', \Civi\Api4\CiviRulesRule::delete(FALSE)->addWhere('id', '=', '$id'))
        ->execute();
    }

    // 2. Actions — safe now: every RuleAction referencing them was removed in step 1.
    if ($actionIds) {
      \Civi\Api4\CiviRulesAction::delete(FALSE)->addWhere('id', 'IN', $actionIds)->execute();
    }

    // 3. Triggers — safe now: no nc_automations rule references them anymore (NO ACTION satisfied).
    if ($triggerIds) {
      \Civi\Api4\CiviRulesTrigger::delete(FALSE)->addWhere('id', 'IN', $triggerIds)->execute();
    }

    // 4. Drop the tracking rows.
    \Civi\Api4\Managed::delete(FALSE)->addWhere('module', '=', 'nc_automations')->execute();

    $log->info('Finished applying update 1001: repair CiviRules managed entities.');
    return TRUE;
  }

}
