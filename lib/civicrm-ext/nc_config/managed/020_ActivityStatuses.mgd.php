<?php

use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_No_reply_auto_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_status',
        'label' => E::ts('No reply (auto)'),
        'value' => '9',
        'name' => 'No reply (auto)',
        'weight' => 9,
        'description' => E::ts('<p>No reply (auto)</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
