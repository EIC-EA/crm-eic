<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_All_Investors_Representat_20',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'All_Investors_Representat_20',
        'title' => E::ts('All Investors & Representatives'),
        'saved_search_id.name' => 'All_Investors',
        'group_type' => [],
        'frontend_title' => E::ts('All Investors & Representatives'),
      ],
      'match' => ['name'],
    ],
  ],
];
