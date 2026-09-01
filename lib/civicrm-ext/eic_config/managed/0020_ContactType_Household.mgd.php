<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'ContactType_Household',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Household',
        'label' => E::ts('Household'),
        'icon' => 'fa-home',
        'is_active' => FALSE,
        'is_reserved' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
];
