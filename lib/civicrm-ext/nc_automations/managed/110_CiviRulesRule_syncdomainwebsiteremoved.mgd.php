<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesRule_syncdomainwebsiteremoved',
    'entity' => 'CiviRulesRule',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'syncdomainwebsiteremoved',
        'label' => E::ts('SyncDomainWebsiteRemoved'),
        'trigger_id.name' => 'deleted_website',
        'description' => E::ts('Removes the Domain field of an Investor or EIC Awardee (Organization) if the Website is removed'),
        'help_text' => '<p>Removes the Domain field of an Investor or EIC Awardee (Organization) if the Website is removed</p>
',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainwebsiteremoved_CiviRulesRuleAction_1',
    'entity' => 'CiviRulesRuleAction',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainwebsiteremoved',
        'action_id.name' => 'sync_investor_domain_on_website_change',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainwebsiteremoved_CiviRulesRuleCondition_1',
    'entity' => 'CiviRulesRuleCondition',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainwebsiteremoved',
        'condition_id.name' => 'contact_has_subtype',
        'condition_params' => [
          'subtype_names' => ['Investor', 'EIC_Awardee'],
          'operator' => 'in one of',
        ],
      ],
    ],
  ],
];
