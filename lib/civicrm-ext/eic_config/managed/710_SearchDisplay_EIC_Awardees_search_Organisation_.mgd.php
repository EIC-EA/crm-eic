<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SearchDisplay_EIC_Awardees_search_Organisation_',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardees_search_Organisation_',
        'label' => E::ts('EIC Awardees search (Organisation)'),
        'saved_search_id.name' => 'EIC_Laureate_Full_search_Organisation_',
        'type' => 'table',
        'settings' => [
          'description' => E::ts(NULL),
          'sort' => [
            ['sort_name', 'ASC'],
          ],
          'limit' => 50,
          'pager' => [
            'expose_limit' => TRUE,
          ],
          'placeholder' => 5,
          'actions' => [
            'tag',
            'update',
            'contact.103',
            'contact.addCase',
          ],
          'classes' => ['table', 'table-striped'],
          'columnMode' => 'custom',
          'toggleColumns' => TRUE,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'sort_name',
              'label' => E::ts('Organisation'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Contact',
                'action' => 'view',
                'join' => '',
                'target' => '_blank',
                'task' => '',
              ],
              'title' => E::ts('View Organisation'),
            ],
            [
              'type' => 'field',
              'key' => 'EIC_Organisation_identifiers.PIC',
              'label' => E::ts('PIC'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Contact',
                'action' => 'view',
                'join' => '',
                'target' => '_blank',
                'task' => '',
              ],
              'title' => E::ts('View Organisation'),
            ],
            [
              'type' => 'field',
              'key' => 'address_primary.country_id:label',
              'label' => E::ts('Country'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'Contact_ActivityContact_Activity_01.subject',
              'label' => E::ts('EIC Project'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Activity',
                'action' => 'view',
                'join' => 'Contact_ActivityContact_Activity_01',
                'target' => 'crm-popup',
                'task' => '',
              ],
              'title' => E::ts('View EIC Project'),
            ],
            [
              'type' => 'field',
              'key' => 'Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Project_Number',
              'label' => E::ts('Project Number'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Activity',
                'action' => 'view',
                'join' => 'Contact_ActivityContact_Activity_01',
                'target' => 'crm-popup',
                'task' => '',
              ],
              'title' => E::ts('View EIC Project'),
            ],
            [
              'type' => 'field',
              'key' => 'Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Category:label',
              'label' => E::ts('Category'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Funding:label',
              'label' => E::ts('Funding'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Funding_Type:label',
              'label' => E::ts('Funding Type'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Cut_Off_Date',
              'label' => E::ts('Cut-Off-Date'),
              'sortable' => TRUE,
            ],
          ],
          'actions_display_mode' => 'menu',
          'headerCount' => TRUE,
          'button' => 'Search',
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
