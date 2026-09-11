<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EU_Survey_Company_Data',
        'title' => E::ts('EU-Survey Company Data'),
        'extends' => 'Organization',
        'weight' => 4,
        'collapse_adv_display' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_CEO_or_project_leader_gender',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'CEO_or_project_leader_gender',
        'label' => E::ts('CEO or project leader gender'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_gender',
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_Founder_Gender',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'Founder_Gender',
        'label' => E::ts('Founder Gender'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_gender',
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_Sector',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'Sector',
        'label' => E::ts('Sector'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_sector',
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    // TRL is stored as a multi-select: a company may run several projects, each
    // with its own Technology Readiness Level, so the company can hold multiple
    // TRL values at once.
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_TRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'TRL',
        'label' => E::ts('Technology Readiness Level (TRL)'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_trl',
        'serialize' => 1,
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    // CRL/BRL/FRL are self-assessed by the beneficiary and stored as single
    // values at company level; the latest survey answer wins (overwrites).
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_CRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'CRL',
        'label' => E::ts('Commercial Readiness Level (CRL) - Self-Assessed'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_crl',
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_BRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'BRL',
        'label' => E::ts('Business Readiness Level (BRL) - Self-Assessed'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_brl',
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_FRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'FRL',
        'label' => E::ts('Funding Readiness Level (FRL) - Self-Assessed'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_frl',
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
