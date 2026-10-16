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
];
