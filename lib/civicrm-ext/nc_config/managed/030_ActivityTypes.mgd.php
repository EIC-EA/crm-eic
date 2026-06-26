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
        'value' => '68',
        'name' => 'Task',
        'weight' => 70,
        'description' => E::ts('<p>Task</p>'),
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
