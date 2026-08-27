<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SearchDisplay_Related_contact_email_activity_Table_1',
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
