<?php

use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesAction_sync_investor_domain_on_website_change',
    'entity' => 'CiviRulesAction',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'sync_investor_domain_on_website_change',
        'label' => E::ts('Sync Investor Domain on Website Change'),
        'class_name' => 'CRM_CivirulesActions_Contact_SyncInvestorDomain',
      ],
    ],
  ],
];
