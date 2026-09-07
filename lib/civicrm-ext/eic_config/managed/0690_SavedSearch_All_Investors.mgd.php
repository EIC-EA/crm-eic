<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_All_Investors',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'All_Investors',
        'label' => E::ts('All Investors'),
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
              'CONTAINS ONE OF',
              [
                'Investor_Representative',
                'Investor',
              ],
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
    'name' => 'SavedSearch_All_Investors_Group_All_Investors_Representat_20',
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