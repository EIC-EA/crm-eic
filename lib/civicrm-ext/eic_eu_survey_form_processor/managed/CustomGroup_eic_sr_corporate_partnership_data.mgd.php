<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_corporate_partnership_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_corporate_partnership_data',
        'title' => E::ts('EIC Corporate Partnership Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_corporate_partnership'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_corp_partnership',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_corporate_partnership_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_corporate_partnership_data',
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
    'name' => 'CustomGroup_eic_sr_corporate_partnership_data_CustomField_partner_type',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_corporate_partnership_data',
        'name' => 'partner_type',
        'label' => E::ts('What type of partner are you targeting?'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'partner_type',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
