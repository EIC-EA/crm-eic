<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_BAS_Progr_19',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_BAS_Programme',
        'title' => E::ts('EIC BAS Programme'),
        'group_type:name' => ['Access Control'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC BAS Programme'),
      ],
      'match' => ['name'],
    ],
  ],
];
