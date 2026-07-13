<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_Community_Progr_22',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Community_Programme',
        'title' => E::ts('EIC Community Programme'),
        'group_type:name' => ['Access Control'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Community Programme'),
      ],
      'match' => ['name'],
    ],
  ],
];
