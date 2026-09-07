<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_Global_Business_Expan_10',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Global_Business_Expan_10',
        'title' => E::ts('EIC Global Business Expansion'),
        'group_type:name' => ['Access Control'],
        'parents:name' => ['EIC_BAS_Programme'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Global Business Expansion'),
      ],
      'match' => ['name'],
    ],
  ],
];
