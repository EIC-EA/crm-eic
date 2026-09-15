<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// NOTE: EIC Community context custom fields are not yet defined (bonus, not
// requested). For now the group only carries the link to the originating EU
// Survey activity.
return [
  [
    'name' => 'CustomGroup_eic_sr_community_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_community_data',
        'title' => E::ts('EIC Community Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_community'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_community',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_community_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_community_data',
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
