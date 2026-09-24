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
    'name' => 'OptionValue_Requested',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Requested'),
        'value' => '7',
        'name' => 'Requested',
        'grouping' => 'Opened',
        'weight' => 6,
        'description' => E::ts('<p>Service request has been submitted and is awaiting handling.</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Planning',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Planning'),
        'value' => '8',
        'name' => 'Planning',
        'grouping' => 'Opened',
        'weight' => 7,
        'description' => E::ts('<p>Service request is being planned.</p>'),
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
