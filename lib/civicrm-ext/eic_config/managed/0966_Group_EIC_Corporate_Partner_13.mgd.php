<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_Corporate_Partner_13',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Corporate_Partner_13',
        'title' => E::ts('EIC Corporate Partnership'),
        'group_type:name' => ['Access Control'],
        'parents:name' => ['EIC_BAS_Programme'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Corporate Partnership'),
      ],
      'match' => ['name'],
    ],
  ],
];
