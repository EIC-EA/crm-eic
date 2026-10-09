<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Internal_contact',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Internal_contact',
        'label' => E::ts('EIC Internal contact'),
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
                  25,
                ],
                [
                  'relationship_type_id',
                  '=',
                  9,
                ],
                [
                  'relationship_type_id',
                  '=',
                  21,
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
    'name' => 'SavedSearch_EIC_Internal_contact_SearchDisplay_EIC_Evaluation_relationships_copy_',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Evaluation_relationships_copy_',
        'label' => E::ts('EIC Internal contact'),
        'saved_search_id.name' => 'EIC_Internal_contact',
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
