<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_VentureM_21',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_VentureMatch',
        'title' => E::ts('EIC VentureMatch'),
        'description' => E::ts('Group use to give access to VentureMatch BAS Programme'),
        'group_type:name' => ['Access Control'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC VentureMatch'),
      ],
      'match' => ['name'],
    ],
  ],
];
