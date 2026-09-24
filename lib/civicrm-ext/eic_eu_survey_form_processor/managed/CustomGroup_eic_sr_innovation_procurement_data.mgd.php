<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_innovation_procurement_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_innovation_procurement_data',
        'title' => E::ts('EIC Innovation Procurement Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_innovation_procurement'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_innov_procurement',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_innovation_procurement_data_CustomField_EU_Survey_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_innovation_procurement_data',
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
    'name' => 'CustomGroup_eic_sr_innovation_procurement_data_CustomField_selling_to_buyers',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_innovation_procurement_data',
        'name' => 'selling_to_public_private_buyers',
        'label' => E::ts('Are you planning to sell your innovative solution to public or private buyers, e.g. through tender opportunities?'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Definition: whether the beneficiary plans to sell their innovative solution to public or private buyers (e.g. through tenders). Source: Form processor (EU-Survey answer). Format: Yes/No. Used for: EIC Innovation Procurement service request context.'),
        'column_name' => 'selling_to_buyers',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_innovation_procurement_data_CustomField_where_in_process',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_innovation_procurement_data',
        'name' => 'where_are_you_in_the_process',
        'label' => E::ts('Where are you in the process?'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Definition: the stage the beneficiary has reached in the procurement/selling process. Source: Form processor (EU-Survey answer). Format: free text as provided by the survey. Used for: EIC Innovation Procurement service request context.'),
        'column_name' => 'where_in_process',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
