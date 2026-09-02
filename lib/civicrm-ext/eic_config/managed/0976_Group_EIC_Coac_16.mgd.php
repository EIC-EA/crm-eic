<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_Coac_16',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Coac_16',
        'title' => E::ts('EIC Coaching'),
        'group_type:name' => ['Access Control'],
        'parents:name' => ['EIC_BAS_Programme'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Coaching'),
      ],
      'match' => ['name'],
    ],
  ],
];
