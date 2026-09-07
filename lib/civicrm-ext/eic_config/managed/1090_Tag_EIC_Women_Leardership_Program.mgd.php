<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Tag_EIC_Women_Leardership_Program',
    'entity' => 'Tag',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Women_Leardership_Program',
        'label' => E::ts('EIC Women Leardership Program'),
        'is_reserved' => TRUE,
        'used_for' => [
          'civicrm_contact',
          'civicrm_activity',
          'civicrm_case',
          'civicrm_file',
        ],
      ],
      'match' => ['name'],
    ],
  ],
];
