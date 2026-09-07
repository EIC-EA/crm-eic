<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'ContactType_EIC_Registered',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Registered',
        'label' => E::ts('EIC Awardee representative'),
        'description' => E::ts('Contact officially listed in EIC Project database'),
        'parent_id.name' => 'Individual',
      ],
      'match' => ['name'],
    ],
  ],
];
