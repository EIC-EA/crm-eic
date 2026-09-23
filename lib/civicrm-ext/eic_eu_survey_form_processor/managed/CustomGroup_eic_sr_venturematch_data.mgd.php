<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_venturematch_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_venturematch_data',
        'title' => E::ts('EIC VentureMatch Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_venturematch'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_venturematch',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_venturematch_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_venturematch_data',
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
    'name' => 'CustomGroup_eic_sr_venturematch_data_CustomField_fundraising_support',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_venturematch_data',
        'name' => 'fundraising_support_needed',
        'label' => E::ts('What fundraising support do you need most?'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'fundraising_support_needed',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_venturematch_data_CustomField_fundraising_within',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_venturematch_data',
        'name' => 'fundraising_within',
        'label' => E::ts('Fundraising within'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Definition: the timeframe within which the beneficiary intends to fundraise. Source: Form processor (EU-Survey answer "Are you fundraising within:"). Format: free text as provided by the survey. Used for: EIC VentureMatch service request context.'),
        'column_name' => 'fundraising_within',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
