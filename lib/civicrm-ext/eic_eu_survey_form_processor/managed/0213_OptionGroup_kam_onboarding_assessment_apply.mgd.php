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
        'data_type' => 'Integer',
        'is_reserved' => FALSE,
        'option_value_fields' => ['name', 'label', 'description'],
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
        'value' => '1',
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
        'value' => '2',
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
        'value' => '3',
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
