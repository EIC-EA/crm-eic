<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_Contacts',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Contacts',
        'label' => E::ts('Contacts'),
        'api_entity' => 'RelationshipCache',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'near_contact_id.sort_name',
            'near_relation:label',
            'far_contact_id.sort_name',
            'description',
            'start_date',
            'end_date',
            'is_active',
            'case_id.subject',
          ],
          'orderBy' => [],
          'where' => [
            [
              'OR',
              [
                [
                  'relationship_type_id',
                  '=',
                  5,
                ],
                [
                  'relationship_type_id',
                  '=',
                  22,
                ],
                [
                  'relationship_type_id',
                  '=',
                  23,
                ],
                [
                  'relationship_type_id',
                  '=',
                  20,
                ],
              ],
            ],
            ['is_active', '=', TRUE],
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
    'name' => 'SavedSearch_Contacts_SearchDisplay_EIC_Internal_contact_copy_',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Internal_contact_copy_',
        'label' => E::ts('Contacts'),
        'saved_search_id.name' => 'Contacts',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [],
          'limit' => 50,
          'pager' => [],
          'placeholder' => 5,
          'actions' => FALSE,
          'classes' => ['table', 'table-striped'],
          'columnMode' => 'custom',
          'toggleColumns' => FALSE,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'near_relation:label',
              'label' => E::ts('Relationship'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'far_contact_id.sort_name',
              'label' => E::ts('With'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Contact',
                'action' => 'view',
                'join' => 'far_contact_id',
                'target' => '_blank',
                'task' => '',
              ],
              'title' => E::ts('View Contact (Far side)'),
            ],
            [
              'type' => 'field',
              'key' => 'case_id.subject',
              'label' => E::ts('Context'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Case',
                'action' => 'view',
                'join' => 'case_id',
                'target' => '_blank',
                'task' => '',
              ],
              'title' => E::ts('View Case'),
            ],
          ],
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
