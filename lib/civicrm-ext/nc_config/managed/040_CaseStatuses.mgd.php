<?php

use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_Onboarded',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Onboarded'),
        'value' => '6',
        'name' => 'Onboarded',
        'grouping' => 'Closed',
        'weight' => 4,
        'description' => E::ts('<p>Onboarded</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Declined',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Declined'),
        'value' => '5',
        'name' => 'Declined',
        'grouping' => 'Closed',
        'weight' => 5,
        'description' => E::ts('<p>Declined</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
