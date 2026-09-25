<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_innonext_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_innonext_data',
        'title' => E::ts('EIC InnoNext Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_innonext'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_innonext',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_innonext_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_innonext_data',
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
];
