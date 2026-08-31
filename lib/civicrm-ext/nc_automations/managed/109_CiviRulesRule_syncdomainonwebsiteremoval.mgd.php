<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesRule_syncdomainonwebsiteremoval',
    'entity' => 'CiviRulesRule',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'syncdomainonwebsiteremoval',
        'label' => E::ts('SyncDomainOnWebsiteRemoval'),
        'trigger_id.name' => 'deleted_website',
        'description' => E::ts('Updates the Domain field of an Investor (Organization) if the Website is deleted'),
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainonwebsiteremoval_CiviRulesRuleAction_1',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainonwebsiteremoval',
        'action_id.name' => 'sync_investor_domain_on_website_change',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainonwebsiteremoval_CiviRulesRuleCondition_1',
    'entity' => 'CiviRulesRuleCondition',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainonwebsiteremoval',
        'condition_id.name' => 'contact_has_subtype',
        'condition_params' => [
          'subtype_names' => ['Investor', 'EIC_Awardee'],
          'operator' => 'in one of',
        ],
      ],
    ],
  ],
];
