<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesRule_syncdomainonwebsitecreation',
    'entity' => 'CiviRulesRule',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'syncdomainonwebsitecreation',
        'label' => E::ts('SyncDomainOnWebsiteCreation'),
        'trigger_id.name' => 'new_website',
        'description' => E::ts('Updates the Domain field of an Investor (Organization) if the Website is created'),
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainonwebsitecreation_CiviRulesRuleAction_1',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainonwebsitecreation',
        'action_id.name' => 'sync_investor_domain_on_website_change',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainonwebsitecreation_CiviRulesRuleCondition_1',
    'entity' => 'CiviRulesRuleCondition',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainonwebsitecreation',
        'condition_id.name' => 'contact_has_subtype',
        'condition_params' => [
          'subtype_names' => ['Investor', 'EIC_Awardee'],
          'operator' => 'in one of',
        ],
      ],
    ],
  ],
];
