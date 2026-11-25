<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_Spin_off_Plan',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Spin-off Plan'),
        'value' => '72',
        'name' => 'Spin-off Plan',
        'weight' => 74,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
