<?php

use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesTrigger_deleted_website',
    'entity' => 'CiviRulesTrigger',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'deleted_website',
        'label' => 'Deleted Website',
        'object_name' => 'Website',
        'op' => 'delete',
      ],
    ],
  ],
];
