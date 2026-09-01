<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_Innovation_Procure_11',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Innovation_Procure_11',
        'title' => E::ts('EIC Innovation Procurement'),
        'group_type:name' => ['Access Control'],
        'parents:name' => ['EIC_BAS_Programme'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Innovation Procurement'),
      ],
      'match' => ['name'],
    ],
  ],
];
