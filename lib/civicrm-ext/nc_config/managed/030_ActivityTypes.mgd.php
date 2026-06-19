<?php

use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_Task',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Task'),
        'value' => '67',
        'name' => 'Task',
        'weight' => 68,
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-bars-progress',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
