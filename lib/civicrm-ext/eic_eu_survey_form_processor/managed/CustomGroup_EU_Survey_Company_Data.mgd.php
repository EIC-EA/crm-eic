<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EU_Survey_Company_Data',
        'title' => E::ts('Self-Assessed information'),
        'extends' => 'Organization',
        'weight' => 4,
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_eu_survey_company_data',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_CEO_or_project_leader_gender',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'CEO_or_project_leader_gender',
        'label' => E::ts('CEO or project leader gender'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_gender',
        'is_view' => TRUE,
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
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'Founder_Gender',
        'label' => E::ts('Founder Gender'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_gender',
        'is_view' => TRUE,
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
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'Sector',
        'label' => E::ts('Sector'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_sector',
        'serialize' => 1,
        'is_view' => TRUE,
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    // TRL/CRL/BRL/FRL are stored as multi-selects: a company may run several
    // projects, each with its own readiness levels, so the company cumulates
    // every value the Awardee self-assessed across all linked projects.
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_TRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'TRL',
        'label' => E::ts('TRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_trl',
        'serialize' => 1,
        'is_view' => TRUE,
        'help_post' => E::ts('Technology Readiness Level (TRL). Self-assessed by the Awardee. This field cumulates all the values reported by the Awardee across every project linked to them.'),
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EU_Survey_Company_Data_CustomField_CRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'CRL',
        'label' => E::ts('CRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_crl',
        'serialize' => 1,
        'is_view' => TRUE,
        'help_post' => E::ts('Commercial Readiness Level (CRL). Self-assessed by the Awardee. This field cumulates all the values reported by the Awardee across every project linked to them.'),
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
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'BRL',
        'label' => E::ts('BRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_brl',
        'serialize' => 1,
        'is_view' => TRUE,
        'help_post' => E::ts('Business Readiness Level (BRL). Self-assessed by the Awardee. This field cumulates all the values reported by the Awardee across every project linked to them.'),
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
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EU_Survey_Company_Data',
        'name' => 'FRL',
        'label' => E::ts('FRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_frl',
        'serialize' => 1,
        'is_view' => TRUE,
        'help_post' => E::ts('Funding Readiness Level (FRL). Self-assessed by the Awardee. This field cumulates all the values reported by the Awardee across every project linked to them.'),
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
