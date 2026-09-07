<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_Case_Resources',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Case_Resources',
        'title' => E::ts('Case Resources'),
        'description' => E::ts('Contacts in this group are listed with their phone number and email when viewing case. You also can send copies of case activities to these contacts.'),
        'group_type:name' => ['Mailing List'],
        'frontend_title' => E::ts('Case Resources'),
      ],
      'match' => ['name'],
    ],
  ],
];
