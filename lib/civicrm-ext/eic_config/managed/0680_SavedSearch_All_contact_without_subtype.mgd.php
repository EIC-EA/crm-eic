<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_All_contact_without_subtype',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'All_contact_without_subtype',
        'label' => E::ts('All contact without subtype'),
        'api_entity' => 'Contact',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'sort_name',
            'contact_type:label',
            'contact_sub_type:label',
          ],
          'orderBy' => [],
          'where' => [
            [
              'contact_sub_type:name',
              'IS EMPTY',
            ],
          ],
          'groupBy' => [],
          'join' => [],
          'having' => [],
        ],
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'SavedSearch_All_contact_without_subtype_Group_All_Contacts_without_sub_24',
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
