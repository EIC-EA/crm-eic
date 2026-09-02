<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Tag_EIC_VentureMatch',
    'entity' => 'Tag',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_VentureMatch',
        'label' => E::ts('EIC VentureMatch'),
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
