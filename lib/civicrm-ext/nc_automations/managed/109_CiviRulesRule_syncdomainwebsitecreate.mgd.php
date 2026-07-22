<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesRule_syncdomainwebsitecreate',
    'entity' => 'CiviRulesRule',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'syncdomainwebsitecreate',
        'label' => E::ts('SyncDomainWebsiteCreate'),
        'trigger_id.name' => 'new_website',
        'description' => E::ts('Adds the Domain field of an Investor or EIC Awarde (Organization) if the Website is provided'),
        'help_text' => '<p>Adds the Domain field of an Investor or EIC Awarde (Organization) if the Website is provided</p>
',
      ],
    ],
  ],
  [
    'name' => 'CiviRulesRule_syncdomainwebsitecreate_CiviRulesRuleCondition_1',
    'entity' => 'CiviRulesRuleCondition',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'rule_id.name' => 'syncdomainwebsitecreate',
        'condition_id.name' => 'contact_has_subtype',
        'condition_params' => [
          'subtype_names' => ['Investor', 'EIC_Awardee'],
          'operator' => 'in one of',
        ],
      ],
    ],
  ],
];
