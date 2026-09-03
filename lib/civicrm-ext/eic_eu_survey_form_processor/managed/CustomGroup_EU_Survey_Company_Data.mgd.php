<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EU_Survey_Company_Data',
        'title' => E::ts('EU-Survey Company Data'),
        'extends' => 'Organization',
        'weight' => 4,
        'collapse_adv_display' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_CEO_or_project_leader_gender',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'CEO_or_project_leader_gender',
        'label' => E::ts('CEO or project leader gender'),
        'html_type' => 'Text',
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
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_Founder_Gender',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'Founder_Gender',
        'label' => E::ts('Founder Gender'),
        'html_type' => 'Text',
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
