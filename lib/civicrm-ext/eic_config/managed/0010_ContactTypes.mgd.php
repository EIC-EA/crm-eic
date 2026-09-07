<?php

use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'ContactType_Investor',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Investor',
        'label' => E::ts('Investor'),
        'parent_id.name' => 'Organization',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'ContactType_Investor_Representative',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Investor_Representative',
        'label' => E::ts('Investor Representative'),
        'description' => E::ts('Investor Representative'),
        'parent_id.name' => 'Individual',
      ],
      'match' => ['name'],
    ],
  ],
];
