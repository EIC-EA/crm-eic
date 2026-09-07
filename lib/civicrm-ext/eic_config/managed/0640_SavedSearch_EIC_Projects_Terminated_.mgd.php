<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Projects_Terminated_',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Projects_Terminated_',
        'label' => E::ts('EIC Projects (Terminated)'),
        'api_entity' => 'Activity',
        'api_params' => [
          'version' => 4,
          'select' => [
            'subject',
            'EIC_Horizon_Europe_Project_information.Project_Number',
            'EIC_Horizon_Europe_Project_information.Category:label',
            'EIC_Horizon_Europe_Project_information.Funding:label',
            'EIC_Horizon_Europe_Project_information.Funding_Type:label',
            'EIC_Horizon_Europe_Project_information.Cut_Off_Date',
            'status_id:label',
            'Activity_Contact_Terminated_Partners_01.sort_name',
          ],
          'orderBy' => [],
          'where' => [
            [
              'activity_type_id:name',
              '=',
              'EIC_Awardee_Project',
            ],
            [
              'EIC_Horizon_Europe_Project_information.Termination:name',
              'IS NOT EMPTY',
            ],
          ],
          'groupBy' => [],
          'join' => [
            [
              'Contact AS Activity_Contact_Terminated_Partners_01',
              'LEFT',
              [
                'EIC_Horizon_Europe_Project_information.Terminated_Partners',
                '=',
                'Activity_Contact_Terminated_Partners_01.id',
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
    'name' => 'SavedSearch_EIC_Projects_Terminated_SearchDisplay_EIC_Project_Active_copy_',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Project_Active_copy_',
        'label' => E::ts('EIC Project - Terminated'),
        'saved_search_id.name' => 'EIC_Projects_Terminated_',
        'type' => 'table',
        'settings' => [
          'description' => E::ts(NULL),
          'sort' => [
            [
              'EIC_Horizon_Europe_Project_information.Cut_Off_Date',
              'DESC',
            ],
          ],
          'limit' => 50,
          'pager' => [],
          'placeholder' => 5,
          'actions' => FALSE,
          'classes' => ['table', 'table-striped'],
          'columnMode' => 'custom',
          'columns' => [
            [
              'type' => 'field',
              'key' => 'subject',
              'label' => E::ts('Subject'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Activity',
                'action' => 'view',
                'join' => '',
                'target' => 'crm-popup',
                'task' => '',
              ],
              'title' => E::ts('View Project'),
            ],
            [
              'type' => 'field',
              'key' => 'EIC_Horizon_Europe_Project_information.Project_Number',
              'label' => E::ts('Project Number'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Activity',
                'action' => 'view',
                'join' => '',
                'target' => 'crm-popup',
                'task' => '',
              ],
              'title' => E::ts('View Project'),
            ],
            [
              'type' => 'field',
              'key' => 'EIC_Horizon_Europe_Project_information.Category:label',
              'label' => E::ts('Category'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'EIC_Horizon_Europe_Project_information.Funding:label',
              'label' => E::ts('Funding'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'EIC_Horizon_Europe_Project_information.Funding_Type:label',
              'label' => E::ts('Funding Type'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'EIC_Horizon_Europe_Project_information.Cut_Off_Date',
              'label' => E::ts('Cut-Off-Date'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'status_id:label',
              'label' => E::ts('Status'),
              'sortable' => TRUE,
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
