<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_Related_contact_email_activity',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Related_contact_email_activity',
        'label' => E::ts('Related contact email activity'),
        'api_entity' => 'Organization',
        'api_params' => [
          'version' => 4,
          'select' => [
            'COUNT(id) AS COUNT_id',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.id',
            'GROUP_CONCAT(DISTINCT sort_name) AS GROUP_CONCAT_sort_name',
            'GROUP_CONCAT(DISTINCT contact_sub_type:label) AS GROUP_CONCAT_contact_sub_type_label',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.subject',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_date_time',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.record_type_id:label',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_type_id:label',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.target_contact_id',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.contact_id.display_name',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.source_contact_id',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.all_contact_id',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.case_id.subject',
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.all_contact_id.display_name',
          ],
          'orderBy' => [],
          'where' => [],
          'groupBy' => [
            'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.id',
          ],
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
                'Contact_RelationshipCache_Contact_01.near_relation:name',
                '=',
                '"Investor at"',
              ],
            ],
            [
              'Activity AS Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01',
              'INNER',
              'ActivityContact',
              [
                'Contact_RelationshipCache_Contact_01.id',
                '=',
                'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.contact_id',
              ],
              [
                'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.record_type_id:name',
                '=',
                '"Activity Targets"',
              ],
              [
                'OR',
                [
                  [
                    'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_type_id:name',
                    '=',
                    '"Email"',
                  ],
                  [
                    'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_type_id:name',
                    '=',
                    '"VentureMatch Email"',
                  ],
                ],
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
    'name' => 'SavedSearch_Related_contact_email_activity_SearchDisplay_Related_contact_email_activity_Table_1',
    'entity' => 'SearchDisplay',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Related_contact_email_activity_Table_1',
        'label' => E::ts('Related contact email activity contact tab'),
        'saved_search_id.name' => 'Related_contact_email_activity',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [
            [
              'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_date_time',
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
              'key' => 'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_type_id:label',
              'label' => E::ts('Type'),
              'sortable' => FALSE,
              'rewrite' => '',
              'title' => NULL,
              'cssRules' => [],
              'icons' => [
                [
                  'field' => 'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_type_id:icon',
                  'side' => 'left',
                ],
              ],
            ],
            [
              'type' => 'field',
              'key' => 'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.subject',
              'label' => E::ts('Email Subject'),
              'sortable' => TRUE,
              'link' => [
                'path' => '',
                'entity' => 'Activity',
                'action' => 'view',
                'join' => 'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01',
                'target' => 'crm-popup',
                'task' => '',
              ],
              'title' => E::ts('View Contact Related Contacts - Contact Activities'),
            ],
            [
              'type' => 'field',
              'key' => 'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.activity_date_time',
              'label' => E::ts('Date'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'Contact_RelationshipCache_Contact_01_Contact_ActivityContact_Activity_01.contact_id.display_name',
              'label' => E::ts('With'),
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
