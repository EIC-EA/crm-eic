<?php

use CRM_NcAutomations_ExtensionUtil as E;

return [
  [
    'name' => 'CiviRulesTrigger_changed_website',
    'entity' => 'CiviRulesTrigger',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'changed_website',
        'label' => 'Changed Website',
        'object_name' => 'Website',
        'op' => 'edit',
      ],
    ],
  ],
];
