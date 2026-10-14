<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesAction_sync_investor_on_representative_change',
    'entity' => 'CiviRulesAction',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'sync_investor_on_representative_change',
        'label' => E::ts('Sync Investor on Representative Change'),
        'class_name' => 'CRM_CivirulesActions_Contact_SyncInvestor',
        'is_active' => TRUE,
      ],
    ],
  ],
  [
    'name' => 'CiviRulesAction_sync_investor_on_representative_change_CiviRulesRuleAction_1',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'sync_represenative_added',
        'action_id.name' => 'sync_investor_on_representative_change',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesAction_sync_investor_on_representative_change_CiviRulesRuleAction_2',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'sync_represenative_fields_change',
        'action_id.name' => 'sync_investor_on_representative_change',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesAction_sync_investor_on_representative_change_CiviRulesRuleAction_3',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'sync_represenative_removed',
        'action_id.name' => 'sync_investor_on_representative_change',
      ],
    ],
  ],
];
