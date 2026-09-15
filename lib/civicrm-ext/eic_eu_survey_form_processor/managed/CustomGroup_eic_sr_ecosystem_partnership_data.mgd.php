<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// NOTE: Context custom fields for the EIC Ecosystem Partnership Service Request
// are not yet defined (to be specified later). For now the group only carries
// the link to the originating EU Survey activity.
return [
  [
    'name' => 'CustomGroup_eic_sr_ecosystem_partnership_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_ecosystem_partnership_data',
        'title' => E::ts('EIC Ecosystem Partnership Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_ecosystem_partnership'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_ecosystem_partnership',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_ecosystem_partnership_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_ecosystem_partnership_data',
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
