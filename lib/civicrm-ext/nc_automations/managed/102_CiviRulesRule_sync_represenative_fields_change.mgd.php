<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesRule_sync_represenative_fields_change',
    'entity' => 'CiviRulesRule',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'sync_represenative_fields_change',
        'label' => E::ts('Sync  Representative Fields Change'),
        'trigger_id.name' => 'changed_contact_custom_data',
        'description' => E::ts('On Representative update sync Investor Org'),
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_sync_represenative_fields_change_CiviRulesRuleAction_1',
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
    'name' => 'CiviRulesRule_sync_represenative_fields_change_CiviRulesRuleCondition_1',
    'entity' => 'CiviRulesRuleCondition',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'sync_represenative_fields_change',
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
