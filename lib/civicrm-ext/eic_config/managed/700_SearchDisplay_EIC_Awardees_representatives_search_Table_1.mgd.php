<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SearchDisplay_EIC_Awardees_representatives_search_Table_1',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardees_representatives_search_Table_1',
        'label' => E::ts('EIC Awardees representatives (search)'),
        'saved_search_id.name' => 'EIC_Awardees_representatives_search_',
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
            'contact.103',
            'contact.relationship',
            'contact.addCase',
            'tag',
            'update',
          ],
          'classes' => ['table', 'table-striped'],
          'columnMode' => 'custom',
          'actions_display_mode' => 'menu',
          'button' => 'Search',
          'toggleColumns' => TRUE,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'display_name',
              'label' => E::ts('Display Name'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'email_primary.email',
              'label' => E::ts('Primary Email Email'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_RelationshipCache_Contact_01_near_contact_id_display_name',
              'label' => E::ts('(List) Contact Related Contacts: Contact (Near side) Display Name'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_RelationshipCache_Contact_01_EIC_Organisation_identifiers_PIC',
              'label' => E::ts('(List) Contact Related Contacts: Organisation identifiers: PIC'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Contact',
                'action' => 'view',
                'join' => 'Contact_RelationshipCache_Contact_01',
                'target' => '_blank',
                'task' => '',
              ],
              'title' => E::ts('View Contact Related Contacts'),
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_RelationshipCache_Contact_01_near_relation_label',
              'label' => E::ts('(List) Contact Related Contacts: Relationship to contact'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_ActivityContact_Activity_01_activity_id_subject',
              'label' => E::ts('(List) Contact Activities: Activity Subject'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Project_Title',
              'label' => E::ts('(List) Contact Activities: EIC Project Activity: Project Title'),
              'sortable' => TRUE,
              'title' => E::ts('View Contact Activities'),
              'link' => [
                'path' => '',
                'entity' => 'Activity',
                'action' => 'view',
                'join' => 'Contact_ActivityContact_Activity_01',
                'target' => 'crm-popup',
                'task' => '',
              ],
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Category_label',
              'label' => E::ts('(List) Contact Activities: EIC Project Activity: Category'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Funding_label',
              'label' => E::ts('(List) Contact Activities: EIC Project Activity: Funding'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Funding_Type_label',
              'label' => E::ts('(List) Contact Activities: EIC Project Activity: Funding Type'),
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
