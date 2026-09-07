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
        'label' => E::ts('EIC Awardee'),
        'description' => E::ts('Organisation formally selected, funded, or recognised by the European Innovation Council (EIC) through its programmes, prizes, or official selection processes'),
        'icon' => 'fa-medal',
        'parent_id.name' => 'Organization',
      ],
      'match' => ['name'],
    ],
  ],
];
