<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_Women_Leardership_Pro_12',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Women_Leardership_Pro_12',
        'title' => E::ts('EIC Women Leardership Program'),
        'group_type:name' => ['Access Control'],
        'parents:name' => ['EIC_BAS_Programme'],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Women Leardership Program'),
      ],
      'match' => ['name'],
    ],
  ],
];
