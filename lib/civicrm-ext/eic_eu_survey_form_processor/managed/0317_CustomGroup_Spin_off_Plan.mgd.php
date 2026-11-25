<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_Spin_off_Plan',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Spin_off_Plan',
        'title' => E::ts('Spin-off Plan'),
        'extends' => 'Activity',
        'extends_entity_column_value:name' => ['Spin-off Plan'],
        'weight' => 32,
        'collapse_adv_display' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_Spin_off_Plan_CustomField_Plan',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'Spin_off_Plan',
        'name' => 'Plan',
        'label' => E::ts('Plan'),
        'data_type:name' => 'Alphanumeric',
        'html_type:name' => 'Drop-down (select list)',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'option_group_id.name' => 'spin_off_plan',
        "column_name": "plan",
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_Spin_off_Plan_CustomField_Project_Type',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'Spin_off_Plan',
        'name' => 'Project_Type',
        'label' => E::ts('Project Type'),
        'data_type' => 'Link',
        'html_type' => 'Link',
        'text_length' => 2047,
        'note_columns' => 60,
        'note_rows' => 4,
        "column_name": "projecttype",
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
