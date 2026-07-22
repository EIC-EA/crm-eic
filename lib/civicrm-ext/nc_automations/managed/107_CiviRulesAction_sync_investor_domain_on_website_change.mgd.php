<?php
use CRM_NcAutomations_ExtensionUtil as E;

return [
    [
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change',
        'entity' => 'CiviRulesAction',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'sync_investor_domain_on_website_change',
                'label' => E::ts('Sync Investor Domain on Website Change'),
                'class_name' => 'CRM_CivirulesActions_Contact_SyncInvestorDomain',
            ],
        ],
    ],
    [
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change_CiviRulesRuleAction_1',
        'entity' => 'CiviRulesRuleAction',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'action_id.name' => 'sync_investor_domain_on_website_change',
            ],
        ],
    ],
    [
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change_CiviRulesRuleAction_2',
        'entity' => 'CiviRulesRuleAction',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'action_id.name' => 'sync_investor_domain_on_website_change',
            ],
        ],
    ],
    [
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change_CiviRulesRuleAction_3',
        'entity' => 'CiviRulesRuleAction',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'action_id.name' => 'sync_investor_domain_on_website_change',
            ],
        ],
    ],
    [
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change_CiviRulesRuleAction_4',
        'entity' => 'CiviRulesRuleAction',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'action_id.name' => 'sync_investor_domain_on_website_change',
            ],
        ],
    ],
    [
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change_CiviRulesRuleAction_5',
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
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change_CiviRulesRuleAction_6',
        'entity' => 'CiviRulesRuleAction',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'rule_id.name' => 'syncdomainonwebsitechange',
                'action_id.name' => 'sync_investor_domain_on_website_change',
            ],
        ],
    ],
    [
        'name' => 'CiviRulesAction_sync_investor_domain_on_website_change_CiviRulesRuleAction_7',
        'entity' => 'CiviRulesRuleAction',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'rule_id.name' => 'syncdomainwebsitecreate',
                'action_id.name' => 'sync_investor_domain_on_website_change',
            ],
        ],
    ],
];
