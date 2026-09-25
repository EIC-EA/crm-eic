<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Context custom fields for the EIC Ecosystem Partnership Service Request:
// the link to the originating EU Survey activity, plus the "what type of other
// support are you interested in" answer that triggers this case (context for
// the service agent).
return [
  [
    'name' => 'CustomGroup_eic_sr_ecosystem_partnership_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
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
    'name' => 'CustomGroup_eic_sr_ecosystem_partnership_data_CustomField_support_interested_in',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_ecosystem_partnership_data',
        'name' => 'support_interested_in',
        'label' => E::ts('What type of other support are you interested in?'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'support_interested_in',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
