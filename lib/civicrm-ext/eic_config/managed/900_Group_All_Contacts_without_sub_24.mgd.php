<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_All_Contacts_without_sub_24',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'All_Contacts_without_sub_24',
        'title' => E::ts('All Contacts without subtype'),
        'saved_search_id.name' => 'All_contact_without_subtype',
        'group_type' => [],
        'frontend_title' => E::ts('All Contacts without subtype'),
      ],
      'match' => ['name'],
    ],
  ],
];
