<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EIC_Project',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Project',
        'title' => E::ts('EIC Project Data'),
        'extends' => 'Case',
        'extends_entity_column_value' => ['4'],
        'weight' => 3,
        'collapse_adv_display' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Project_CustomField_EIC_Title',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Project',
        'name' => 'EIC_Title',
        'label' => E::ts('EIC Title'),
        'html_type' => 'Text',
        'default_value' => 'EIC Awardee Onboarding',
        'is_view' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Project_CustomField_Organisation_PIC_number',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Project',
        'name' => 'Organisation_PIC_number',
        'label' => E::ts('Organisation PIC number'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
