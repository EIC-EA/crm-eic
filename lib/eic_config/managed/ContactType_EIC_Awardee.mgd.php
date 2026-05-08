<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'ContactType_EIC_Awardee',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardee',
        'label' => E::ts('EIC Awardee Organization'),
        'parent_id.name' => 'Organization',
      ],
      'match' => ['name'],
    ],
  ],
];
