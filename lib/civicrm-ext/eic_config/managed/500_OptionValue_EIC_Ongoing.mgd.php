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
];
