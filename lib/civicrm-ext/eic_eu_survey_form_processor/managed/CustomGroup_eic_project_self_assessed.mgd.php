<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Self-Assessed information captured per EIC Project. These fields mirror the
// company-level "Self-Assessed information" group but are stored on the
// EIC Project activity (EIC_Awardee_Project): each holds the single value the
// Awardee self-assessed for THIS project (the company-level group cumulates the
// values across all the Awardee's projects). Populated by the EIC Accelerator
// Onboarding Survey import. Option lists are reused from the EU-Survey option
// groups so values are consistent with the company-level fields.
return [
  [
    'name' => 'CustomGroup_eic_project_self_assessed',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_project_self_assessed',
        'title' => E::ts('Self-Assessed information (by company)'),
        'extends' => 'Activity',
        'extends_entity_column_value:name' => ['EIC_Awardee_Project'],
        'collapse_adv_display' => TRUE,
        'is_public' => FALSE,
        'table_name' => 'civicrm_value_srm_eic_project_self_assessed',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_project_self_assessed_CustomField_CEO_or_project_leader_gender',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_project_self_assessed',
        'name' => 'CEO_or_project_leader_gender',
        'label' => E::ts('CEO or project leader gender'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_gender',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('CEO or project leader gender. Self-assessed by a representative of the EIC Awardee via the onboarding survey. This value applies to this specific project.'),
        'column_name' => 'ceo_or_project_leader_gender',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_project_self_assessed_CustomField_Founder_Gender',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_project_self_assessed',
        'name' => 'Founder_Gender',
        'label' => E::ts('Founder Gender'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_gender',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Founder gender. Self-assessed by a representative of the EIC Awardee via the onboarding survey. This value applies to this specific project.'),
        'column_name' => 'founder_gender',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_project_self_assessed_CustomField_Sector',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_project_self_assessed',
        'name' => 'Sector',
        'label' => E::ts('Sector'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_sector',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Sector. Self-assessed by a representative of the EIC Awardee via the onboarding survey. This value applies to this specific project.'),
        'column_name' => 'sector',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_project_self_assessed_CustomField_TRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_project_self_assessed',
        'name' => 'TRL',
        'label' => E::ts('TRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_trl',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Technology Readiness Level (TRL). Self-assessed by a representative of the EIC Awardee via the onboarding survey. This value applies to this specific project.'),
        'column_name' => 'trl',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_project_self_assessed_CustomField_CRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_project_self_assessed',
        'name' => 'CRL',
        'label' => E::ts('CRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_crl',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Commercial Readiness Level (CRL). Self-assessed by a representative of the EIC Awardee via the onboarding survey. This value applies to this specific project.'),
        'column_name' => 'crl',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_project_self_assessed_CustomField_BRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_project_self_assessed',
        'name' => 'BRL',
        'label' => E::ts('BRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_brl',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Business Readiness Level (BRL). Self-assessed by a representative of the EIC Awardee via the onboarding survey. This value applies to this specific project.'),
        'column_name' => 'brl',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_project_self_assessed_CustomField_FRL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_project_self_assessed',
        'name' => 'FRL',
        'label' => E::ts('FRL'),
        'data_type' => 'String',
        'html_type' => 'Select',
        'option_group_id.name' => 'eu_survey_frl',
        'is_view' => TRUE,
        'text_length' => 255,
        'help_post' => E::ts('Funding Readiness Level (FRL). Self-assessed by a representative of the EIC Awardee via the onboarding survey. This value applies to this specific project.'),
        'column_name' => 'frl',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
