<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'ContactType_EIC_Organisation',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Organisation',
        'label' => E::ts('EIC Organisation'),
        'parent_id.name' => 'Organization',
      ],
      'match' => ['name'],
    ],
  ],
];
