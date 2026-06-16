<?php

use CRM_NcConfig_ExtensionUtil as E;

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
  [
    'name' => 'ContactType_Investor',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Investor',
        'label' => E::ts('Investor'),
        'parent_id.name' => 'Organization',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'ContactType_Investor_Representative',
    'entity' => 'ContactType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Investor_Representative',
        'label' => E::ts('Investor Representative'),
        'description' => E::ts('Investor Representative'),
        'parent_id.name' => 'Individual',
      ],
      'match' => ['name'],
    ],
  ],
];
