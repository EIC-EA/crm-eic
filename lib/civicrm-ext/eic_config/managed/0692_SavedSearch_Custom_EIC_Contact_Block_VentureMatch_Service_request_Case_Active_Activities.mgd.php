<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_Custom_EIC_Contact_Block_VentureMatch_Service_request_Case_Active_Activities',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Custom_EIC_Contact_Block_VentureMatch_Service_request_Case_Active_Activities',
        'label' => 'Custom - EIC Contact Block VentureMatch Service request Case Active Activities',
        'form_values' => [
          'join' => [
            'Activity_ActivityContact_Contact_01' => 'Target Contacts',
            'Activity_ActivityContact_Contact_02' => 'Source Contact',
            'Activity_ActivityContact_Contact_03' => 'Assignee Contacts',
          ],
        ],
        'api_entity' => 'Activity',
        'api_params' => [
          'version' => 4,
          'select' => [
            'activity_date_time',
            'subject',
            'activity_type_id:label',
            'status_id:label',
            'GROUP_CONCAT(DISTINCT Activity_ActivityContact_Contact_02.sort_name) AS GROUP_CONCAT_Activity_ActivityContact_Contact_02_sort_name',
            'GROUP_CONCAT(DISTINCT Activity_ActivityContact_Contact_01.sort_name ORDER BY Activity_ActivityContact_Contact_01.sort_name ASC) AS GROUP_CONCAT_Activity_ActivityContact_Contact_01_sort_name_Activity_ActivityContact_Contact_01_sort_name',
            'Activity_CaseActivity_Case_01.id',
            'GROUP_CONCAT(DISTINCT Activity_ActivityContact_Contact_03.sort_name) AS GROUP_CONCAT_Activity_ActivityContact_Contact_03_sort_name',
          ],
          'orderBy' => [],
          'where' => [
            [
              'Activity_CaseActivity_Case_01.case_type_id:name',
              '=',
              'eic_sr_venturematch',
            ],
            [
              'Activity_CaseActivity_Case_01.end_date',
              'IS EMPTY',
            ],
          ],
          'groupBy' => [
            'id',
            'Activity_CaseActivity_Case_01.id',
          ],
          'join' => [
            [
              'Contact AS Activity_ActivityContact_Contact_01',
              'LEFT',
              'ActivityContact',
              [
                'id',
                '=',
                'Activity_ActivityContact_Contact_01.activity_id',
              ],
              [
                'Activity_ActivityContact_Contact_01.record_type_id:name',
                '=',
                '"Activity Targets"',
              ],
            ],
            [
              'Case AS Activity_CaseActivity_Case_01',
              'LEFT',
              'CaseActivity',
              [
                'id',
                '=',
                'Activity_CaseActivity_Case_01.activity_id',
              ],
            ],
            [
              'Contact AS Activity_ActivityContact_Contact_02',
              'LEFT',
              'ActivityContact',
              [
                'id',
                '=',
                'Activity_ActivityContact_Contact_02.activity_id',
              ],
              [
                'Activity_ActivityContact_Contact_02.record_type_id:name',
                '=',
                '"Activity Source"',
              ],
            ],
            [
              'Contact AS Activity_ActivityContact_Contact_03',
              'LEFT',
              'ActivityContact',
              [
                'id',
                '=',
                'Activity_ActivityContact_Contact_03.activity_id',
              ],
              [
                'Activity_ActivityContact_Contact_03.record_type_id:name',
                '=',
                '"Activity Assignees"',
              ],
            ],
          ],
          'having' => [],
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'SavedSearch_Custom_EIC_Contact_Block_VentureMatch_Service_request_Case_Active_Activities_SearchDisplay_VentureMatch_Service_request_Activities_summary_copy_',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'VentureMatch_Service_request_Activities_summary_copy_',
        'label' => 'VentureMatch Service request Activities summary',
        'saved_search_id.name' => 'Custom_EIC_Contact_Block_VentureMatch_Service_request_Case_Active_Activities',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [
            [
              'activity_date_time',
              'DESC',
            ],
          ],
          'limit' => 10,
          'pager' => [
            'show_count' => TRUE,
            'hide_single' => TRUE,
          ],
          'placeholder' => 5,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'activity_date_time',
              'label' => 'Date',
              'sortable' => TRUE,
              'format' => 'dateformatshortdate',
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'subject',
              'label' => 'Subject',
              'sortable' => TRUE,
              'editable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'activity_type_id:label',
              'label' => 'Activity Type',
              'sortable' => TRUE,
              'icons' => [
                [
                  'field' => 'activity_type_id:icon',
                  'side' => 'left',
                ],
              ],
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Activity_ActivityContact_Contact_01_sort_name_Activity_ActivityContact_Contact_01_sort_name',
              'label' => 'With',
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Activity_ActivityContact_Contact_03_sort_name',
              'label' => 'Assignee',
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'status_id:label',
              'label' => 'Status',
              'sortable' => TRUE,
              'editable' => TRUE,
            ],
            [
              'text' => '',
              'style' => 'default',
              'size' => 'btn-xs',
              'icon' => 'fa-bars',
              'links' => [
                [
                  'entity' => 'Activity',
                  'action' => 'view',
                  'join' => '',
                  'target' => 'crm-popup',
                  'icon' => 'fa-external-link',
                  'text' => 'View Activity',
                  'style' => 'default',
                  'path' => '',
                  'task' => '',
                  'conditions' => [],
                ],
                [
                  'entity' => 'Activity',
                  'action' => 'update',
                  'join' => '',
                  'target' => 'crm-popup',
                  'icon' => 'fa-pencil',
                  'text' => 'Update Activity',
                  'style' => 'default',
                  'path' => '',
                  'task' => '',
                  'conditions' => [],
                ],
                [
                  'entity' => 'Activity',
                  'action' => 'delete',
                  'join' => '',
                  'target' => 'crm-popup',
                  'icon' => 'fa-trash',
                  'text' => 'Delete Activity',
                  'style' => 'danger',
                  'path' => '',
                  'task' => '',
                  'conditions' => [],
                ],
              ],
              'type' => 'menu',
              'alignment' => 'text-right',
            ],
          ],
          'actions' => FALSE,
          'classes' => [
            'table',
            'table-striped',
          ],
          'columnMode' => 'custom',
          'cssRules' => [],
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
