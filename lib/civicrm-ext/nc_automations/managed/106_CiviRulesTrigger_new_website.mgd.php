<?php

use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesTrigger_new_website',
    'entity' => 'CiviRulesTrigger',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'new_website',
        'label' => 'New Website',
        'object_name' => 'Website',
        'op' => 'create',
      ],
    ],
  ],
];
