<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_global_business_expansion_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_global_business_expansion_data',
        'title' => E::ts('EIC Global Business Expansion Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_global_business_expansion'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_gbe',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_global_business_expansion_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_global_business_expansion_data',
        'name' => 'EU_Survey_Activity',
        'label' => E::ts('EU Survey (activity)'),
        'data_type' => 'EntityReference',
        'html_type' => 'Autocomplete-Select',
        'fk_entity' => 'Activity',
        'is_view' => TRUE,
        'column_name' => 'eu_survey_activity',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_global_business_expansion_data_CustomField_markets',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_global_business_expansion_data',
        'name' => 'markets',
        'label' => E::ts('Which markets?'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'markets',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_global_business_expansion_data_CustomField_main_barrier',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_global_business_expansion_data',
        'name' => 'main_barrier',
        'label' => E::ts('What is your main barrier?'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'main_barrier',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
