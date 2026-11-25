<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_organ',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'kam_onboarding_assessment_organ',
        'title' => E::ts('KAM Onboarding Assessment Organisation Type'),
        'data_type' => 'String',
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => FALSE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_organ_OptionValue_Research_institution_University_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_organ',
        'label' => E::ts('Research institution (University)'),
        'value' => 'Research institution Univerity',
        'name' => 'Research institution (University)',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_organ_OptionValue_Research_institution_research_centre_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_organ',
        'label' => E::ts('Research institution (research centre)'),
        'value' => 'Research institution reesearch centre',
        'name' => 'Research institution (research centre)',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_organ_OptionValue_SME',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_organ',
        'label' => E::ts('SME'),
        'value' => 'SME',
        'name' => 'SME',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_organ_OptionValue_Large_enterprise_Corporate',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_organ',
        'label' => E::ts('Large enterprise / Corporate'),
        'value' => 'Large enterprise Corporate',
        'name' => 'Large enterprise / Corporate',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_organ_OptionValue_Other',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_organ',
        'label' => E::ts('Other'),
        'value' => 'Other',
        'name' => 'Other',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
