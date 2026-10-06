<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Evaluation_relationships',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Evaluation_relationships',
        'label' => E::ts('EIC Project contact'),
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
            'EIC_Horizon_europe_Relationship.Horizon_Europe_Project.subject',
          ],
          'orderBy' => [],
          'where' => [
            [
              'OR',
              [
                [
                  'relationship_type_id',
                  '=',
                  11,
                ],
                [
                  'relationship_type_id',
                  '=',
                  12,
                ],
                [
                  'relationship_type_id',
                  '=',
                  13,
                ],
                [
                  'relationship_type_id',
                  '=',
                  14,
                ],
                [
                  'relationship_type_id',
                  '=',
                  15,
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
    'name' => 'SavedSearch_EIC_Evaluation_relationships_SearchDisplay_EIC_Evaluation_relationships',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Evaluation_relationships',
        'label' => E::ts('EIC Evaluation relationships'),
        'saved_search_id.name' => 'EIC_Evaluation_relationships',
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
                'target' => '',
                'task' => '',
              ],
              'title' => E::ts('View Contact (Far side)'),
            ],
            [
              'type' => 'field',
              'key' => 'EIC_Horizon_europe_Relationship.Horizon_Europe_Project.subject',
              'label' => E::ts('Project'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Activity',
                'action' => 'view',
                'join' => 'EIC_Horizon_europe_Relationship.Horizon_Europe_Project',
                'target' => 'crm-popup',
                'task' => '',
              ],
              'title' => E::ts('View EIC Project Relationship: Horizon Europe Project'),
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
