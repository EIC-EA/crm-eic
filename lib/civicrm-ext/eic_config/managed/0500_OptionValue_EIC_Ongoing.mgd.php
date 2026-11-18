<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_Ongoing',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_status',
        'label' => E::ts('Ongoing'),
        'value' => '10',
        'name' => 'Ongoing',
        'weight' => 10,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_To_plan',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_status',
        'label' => E::ts('To plan'),
        'value' => '11',
        'name' => 'To plan',
        'weight' => 11,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],  
];
