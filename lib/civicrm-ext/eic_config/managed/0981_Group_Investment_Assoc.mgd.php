<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_Investment_Assoc',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Investment_Assoc',
        'title' => 'Investment Associate',
        'group_type' => [],
        'parents:name' => ['EIC_VentureMatch'],
        'is_reserved' => TRUE,
        'frontend_title' => 'Investment Associate',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
