<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Tag_EIC_Tech_to_Market_Programme',
    'entity' => 'Tag',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Tech_to_Market_Programme',
        'label' => E::ts('EIC Tech to Market Programme'),
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
