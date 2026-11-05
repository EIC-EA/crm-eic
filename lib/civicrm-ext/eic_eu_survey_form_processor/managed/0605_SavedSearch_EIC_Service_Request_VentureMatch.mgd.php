<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Service_Request_VentureMatch',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Service_Request_VentureMatch',
        'label' => E::ts('EIC Service Request - VentureMatch'),
        'api_entity' => 'Case',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'subject',
            'Case_CaseContact_Contact_01.sort_name',
            'status_id:label',
            'start_date',
            'end_date',
            'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.near_contact_id.display_name',
            'case_type_id:label',
          ],
          'orderBy' => [],
          'where' => [
            [
              'case_type_id:name',
              'CONTAINS ONE OF',
              [
                'eic_sr_venturematch',
              ],
            ],
          ],
          'groupBy' => [],
          'join' => [
            [
              'Contact AS Case_CaseContact_Contact_01',
              'LEFT',
              'CaseContact',
              [
                'id',
                '=',
                'Case_CaseContact_Contact_01.case_id',
              ],
            ],
            [
              'Case AS Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01',
              'LEFT',
              'RelationshipCache',
              [
                'Case_CaseContact_Contact_01.id',
                '=',
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.far_contact_id',
              ],
              [
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.near_contact_id',
                '=',
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.case_manager_id',
              ],
            ],
          ],
          'having' => [],
        ],
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'SavedSearch_EIC_Service_Request_VentureMatch_SearchDisplay_EIC_Service_Requests_copy_',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Service_Requests_copy_',
        'label' => E::ts('EIC Service Request - VentureMatch'),
        'saved_search_id.name' => 'EIC_Service_Request_VentureMatch',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [
            ['start_date', 'ASC'],
          ],
          'limit' => 50,
          'pager' => [],
          'placeholder' => 5,
          'actions' => TRUE,
          'classes' => [
            'table',
            'table-striped',
            'crm-sticky-header',
          ],
          'columnMode' => 'custom',
          'actions_display_mode' => 'menu',
          'columns' => [
            [
              'type' => 'field',
              'key' => 'subject',
              'label' => E::ts('Programme'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Case',
                'action' => 'view',
                'join' => '',
                'target' => 'crm-popup',
                'task' => '',
              ],
              'title' => E::ts('View Case'),
            ],
            [
              'type' => 'field',
              'key' => 'Case_CaseContact_Contact_01.sort_name',
              'label' => E::ts('Organisation'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'status_id:label',
              'label' => E::ts('Case Status'),
              'sortable' => TRUE,
              'colors' => [
                [
                  'field' => 'status_id:color',
                ],
              ],
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'start_date',
              'label' => E::ts('Start Date'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'end_date',
              'label' => E::ts('End Date'),
              'sortable' => TRUE,
              'editable' => TRUE,
              'show_linebreaks' => FALSE,
              'format' => 'dateformatshortdate',
            ],
            [
              'type' => 'field',
              'key' => 'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.near_contact_id.display_name',
              'label' => E::ts('KAM'),
              'sortable' => TRUE,
            ],
          ],
          'headerCount' => TRUE,
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
