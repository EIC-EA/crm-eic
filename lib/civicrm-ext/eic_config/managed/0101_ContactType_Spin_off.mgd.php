<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'ContactType_Spin_off',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Spin_off',
        'label' => E::ts('Spin-off'),
        'parent_id.name' => 'Organization',
      ],
      'match' => ['name'],
    ],
  ],
];
