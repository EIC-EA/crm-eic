<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_apply',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'kam_onboarding_assessment_apply',
        'title' => E::ts('KAM Onboarding Assessment Apply for Accellerator'),
        'data_type' => 'String',
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => FALSE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_apply_OptionValue_Yes',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_apply',
        'label' => E::ts('Yes'),
        'value' => 'Yes',
        'name' => 'Yes',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_apply_OptionValue_No',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_apply',
        'label' => E::ts('No'),
        'value' => 'No',
        'name' => 'No',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_kam_onboarding_assessment_apply_OptionValue_Not_sure_yet',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'kam_onboarding_assessment_apply',
        'label' => E::ts('Not sure yet'),
        'value' => 'Not sure yet',
        'name' => 'Not sure yet',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
