<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Service_Request_All_copy_',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Service_Request_All_copy_',
        'label' => E::ts('Unassigned Service Requests'),
        'api_entity' => 'Case',
        'api_params' => [
          'version' => 4,
          'select' => [
            'case_type_id:label',
            'Case_CaseContact_Contact_01.sort_name',
            'Case_CaseContact_Contact_01.EU_Survey_Company_Data.Sector:label',
            'status_id:label',
            'start_date',
            'end_date',
          ],
          'orderBy' => [],
          'where' => [
            [
              'case_type_id:name',
              'CONTAINS ONE OF',
              [
                'eic_sr_community',
                'eic_sr_women_leadership',
                'eic_sr_venturematch',
                'eic_sr_international_trade_fairs',
                'eic_sr_innovation_procurement',
                'eic_sr_innonext',
                'eic_sr_global_business_expansion',
                'eic_sr_ecosystem_partnership',
                'eic_sr_corporate_partnership',
                'eic_sr_coaching',
              ],
            ],
            [
              'case_manager_id',
              'IS EMPTY',
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
              [
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.id',
                '=',
                'id',
              ],
              [
                'Case_CaseContact_Contact_01_Contact_RelationshipCache_Case_01.is_active',
                '=',
                TRUE,
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
    'name' => 'SavedSearch_EIC_Service_Request_All_copy_SearchDisplay_EIC_Service_Request_All_copy_Table_5',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Service_Request_All_copy_Table_5',
        'label' => E::ts('Unassigned Service Requests'),
        'saved_search_id.name' => 'EIC_Service_Request_All_copy_',
        'type' => 'table',
        'settings' => [
          'description' => '',
          'sort' => [
            ['start_date', 'ASC'],
          ],
          'limit' => 50,
          'pager' => [],
          'placeholder' => 5,
          'actions' => ['case.addRole', 'tag', 'update'],
          'classes' => ['table', 'table-striped'],
          'columnMode' => 'auto',
          'actions_display_mode' => 'menu',
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
