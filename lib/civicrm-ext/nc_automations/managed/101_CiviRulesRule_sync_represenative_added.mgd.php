<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesRule_sync_represenative_added',
    'entity' => 'CiviRulesRule',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'sync_represenative_added',
        'label' => E::ts('Sync  Representative Added'),
        'trigger_id.name' => 'new_relationship',
        'description' => E::ts('On Representative added sync Investor Org'),
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_sync_represenative_added_CiviRulesRuleAction_1',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'sync_represenative_added',
        'action_id.name' => 'sync_investor_on_representative_change',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_sync_represenative_added_CiviRulesRuleCondition_1',
    'entity' => 'CiviRulesRuleCondition',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'sync_represenative_added',
        'condition_id.name' => 'contact_has_subtype',
        'condition_params' => [
          'subtype_names' => [
            'Investor_Representative',
          ],
          'operator' => 'in one of',
        ],
      ],
    ],
  ],
];
