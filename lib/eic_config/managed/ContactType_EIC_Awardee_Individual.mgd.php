<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'ContactType_EIC_Awardee_Individual',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardee_Individual',
        'label' => E::ts('EIC Awardee Individual'),
        'parent_id.name' => 'Individual',
      ],
      'match' => ['name'],
    ],
  ],
];
