<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Awardees_representatives_search_',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardees_representatives_search_',
        'label' => E::ts('EIC Awardees representatives (search)'),
        'api_entity' => 'Individual',
        'api_params' => [
          'version' => 4,
          'select' => [
            'display_name',
            'GROUP_CONCAT(DISTINCT Contact_RelationshipCache_Contact_01.near_contact_id.display_name) AS GROUP_CONCAT_Contact_RelationshipCache_Contact_01_near_contact_id_display_name',
            'GROUP_CONCAT(DISTINCT Contact_RelationshipCache_Contact_01.EIC_Organisation_identifiers.PIC) AS GROUP_CONCAT_Contact_RelationshipCache_Contact_01_EIC_Organisation_identifiers_PIC',
            'GROUP_CONCAT(DISTINCT Contact_RelationshipCache_Contact_01.near_relation:label) AS GROUP_CONCAT_Contact_RelationshipCache_Contact_01_near_relation_label',
            'GROUP_CONCAT(DISTINCT Contact_ActivityContact_Activity_01.activity_id.subject) AS GROUP_CONCAT_Contact_ActivityContact_Activity_01_activity_id_subject',
            'email_primary.email',
            'GROUP_CONCAT(DISTINCT Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Category:label) AS GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Category_label',
            'GROUP_CONCAT(DISTINCT Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Funding:label) AS GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Funding_label',
            'GROUP_CONCAT(DISTINCT Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Funding_Type:label) AS GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Funding_Type_label',
            'GROUP_CONCAT(DISTINCT Contact_ActivityContact_Activity_01.EIC_Horizon_Europe_Project_information.Project_Title) AS GROUP_CONCAT_Contact_ActivityContact_Activity_01_EIC_Horizon_Europe_Project_information_Project_Title',
          ],
          'orderBy' => [],
          'where' => [],
          'groupBy' => ['id'],
          'join' => [
            [
              'Contact AS Contact_RelationshipCache_Contact_01',
              'INNER',
              'RelationshipCache',
              [
                'id',
                '=',
                'Contact_RelationshipCache_Contact_01.far_contact_id',
              ],
              [
                'Contact_RelationshipCache_Contact_01.contact_sub_type:name',
                'CONTAINS',
                ['EIC_Awardee'],
              ],
            ],
            [
              'Activity AS Contact_ActivityContact_Activity_01',
              'LEFT',
              'ActivityContact',
              [
                'id',
                '=',
                'Contact_ActivityContact_Activity_01.contact_id',
              ],
              [
                'Contact_ActivityContact_Activity_01.record_type_id:name',
                '=',
                '"Activity Targets"',
              ],
              [
                'Contact_ActivityContact_Activity_01.activity_type_id:name',
                '=',
                '"EIC_Awardee_Project"',
              ],
            ],
            [
              'Tag AS Contact_RelationshipCache_Contact_01_Contact_EntityTag_Tag_01',
              'LEFT',
              'EntityTag',
              [
                'Contact_RelationshipCache_Contact_01.id',
                '=',
                'Contact_RelationshipCache_Contact_01_Contact_EntityTag_Tag_01.entity_id',
              ],
              [
                'Contact_RelationshipCache_Contact_01_Contact_EntityTag_Tag_01.entity_table',
                '=',
                '\'civicrm_contact\'',
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
    'name' => 'SavedSearch_EIC_Awardees_representatives_search_SearchDisplay_EIC_Awardees_representatives_search_Table_1',
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
