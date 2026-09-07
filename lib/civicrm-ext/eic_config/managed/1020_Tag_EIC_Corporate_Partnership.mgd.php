<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Tag_EIC_Corporate_Partnership',
    'entity' => 'Tag',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Corporate_Partnership',
        'label' => E::ts('EIC Corporate Partnership'),
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
