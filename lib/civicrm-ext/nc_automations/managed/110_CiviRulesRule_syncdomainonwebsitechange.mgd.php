<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesRule_syncdomainonwebsitechange',
    'entity' => 'CiviRulesRule',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'syncdomainonwebsitechange',
        'label' => E::ts('SyncDomainOnWebsiteChange'),
        'trigger_id.name' => 'changed_website',
        'description' => E::ts('Updates the Domain field of an Investor (Organization) if the Website is changed'),
        'help_text' => '<p>Updates the Domain field of an Investor or EIC Awardee (Organization) if the Website is changed</p>
',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainonwebsitechange_CiviRulesRuleAction_1',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainonwebsitechange',
        'action_id.name' => 'sync_investor_domain_on_website_change',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainonwebsitechange_CiviRulesRuleCondition_1',
    'entity' => 'CiviRulesRuleCondition',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainonwebsitechange',
        'condition_id.name' => 'contact_has_subtype',
        'condition_params' => [
          'subtype_names' => ['Investor', 'EIC_Awardee'],
          'operator' => 'in one of',
        ],
      ],
    ],
  ],
];
