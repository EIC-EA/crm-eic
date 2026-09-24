<?php

use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EU_Survey_Investor_Onboarding',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EU_Survey_Investor_Onboarding',
        'title' => E::ts('EU Survey - Investor Onboarding'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_investor_onboarding'],
        'weight' => 13,
        'collapse_adv_display' => TRUE,
        'table_name' => "civicrm_value_srm_eu_survey_investor_onboarding",
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Investor_Onboarding_CustomField_Organisation_name',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Investor_Onboarding',
        'name' => 'Organisation_name',
        'label' => E::ts('Organisation name'),
        'html_type' => 'Text',
        'text_length' => 1024,
        'note_columns' => 60,
        'note_rows' => 4,
        "column_name" => "organisation_name",
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Investor_Onboarding_CustomField_Organisation_domain',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Investor_Onboarding',
        'name' => 'Organisation_domain',
        'label' => E::ts('Organisation domain'),
        'html_type' => 'Text',
        'text_length' => 512,
        'note_columns' => 60,
        'note_rows' => 4,
        "column_name" => "organisation_domain",
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
