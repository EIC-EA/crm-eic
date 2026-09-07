<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_All_EIC_Awardees',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'All_EIC_Awardees',
        'label' => E::ts('All EIC Awardees'),
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
              ['EIC_Awardee', 'EIC_Registered'],
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
    'name' => 'SavedSearch_All_EIC_Awardees_Group_EIC_Awar_16',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awar_16',
        'title' => E::ts('EIC Awardees'),
        'description' => E::ts('Group with all the EIC awardees contacts.
This group is use in the ACL to grant access to the contact data'),
        'saved_search_id.name' => 'All_EIC_Awardees',
        'group_type' => [],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Awardees'),
      ],
      'match' => ['name'],
    ],
  ],
];
