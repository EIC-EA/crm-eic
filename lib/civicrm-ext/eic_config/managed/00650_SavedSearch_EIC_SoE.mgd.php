<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_SoE',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_SoE',
        'label' => E::ts('EIC SoE'),
        'api_entity' => 'Activity',
        'api_params' => [
          'version' => 4,
          'select' => [
            'subject',
            'EIC_Horizon_Europe_Project_information.Project_Number',
            'EIC_Horizon_Europe_Project_information.Category:label',
            'EIC_Horizon_Europe_Project_information.Funding:label',
            'EIC_Horizon_Europe_Project_information.Cut_Off_Date',
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
              'IS EMPTY',
            ],
            [
              'EIC_Horizon_Europe_Project_information.Category:name',
              '=',
              'HE_SoE',
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
    'name' => 'SavedSearch_EIC_SoE_SearchDisplay_EIC_Project_Active_copy_',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Project_Active_copy_',
        'label' => E::ts('EIC Seal of Excellence'),
        'saved_search_id.name' => 'EIC_SoE',
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
