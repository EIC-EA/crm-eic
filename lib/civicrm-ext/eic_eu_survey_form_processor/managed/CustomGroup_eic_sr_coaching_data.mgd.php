<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_coaching_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_coaching_data',
        'title' => E::ts('EIC Coaching Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_coaching'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_coaching',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_coaching_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_coaching_data',
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
    'name' => 'CustomGroup_eic_sr_coaching_data_CustomField_support_required',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_coaching_data',
        'name' => 'support_required',
        'label' => E::ts('Support required:'),
        'html_type' => 'Text',
        'text_length' => 255,
        'weight' => 2,
        'help_post' => E::ts('Definition: the type of industry expert or coaching support the beneficiary requires. Source: Form processor (EU-Survey answer "What type of support?"). Format: free text as provided by the survey. Used for: EIC Coaching service request context.'),
        'column_name' => 'support_required',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_coaching_data_CustomField_main_challenge',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_coaching_data',
        'name' => 'main_challenge',
        'label' => E::ts('Main challenges'),
        'html_type' => 'Text',
        'text_length' => 255,
        'weight' => 3,
        'help_post' => E::ts('Definition: the main challenge the beneficiary reported. Source: Form processor (EU-Survey answer "What is your main challenge?"). Format: free text as provided by the survey. Used for: EIC Coaching service request context.'),
        'column_name' => 'main_challenge',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
