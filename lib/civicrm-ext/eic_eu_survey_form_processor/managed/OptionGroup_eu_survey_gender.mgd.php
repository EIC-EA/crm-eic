<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eu_survey_gender',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_gender',
        'title' => E::ts('EU-Survey Gender'),
        'description' => E::ts('Gender options offered by the EU-Survey beneficiary onboarding form.'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_gender_OptionValue_Female',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_gender_1', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_gender',
        'label' => E::ts('Female'),
        'value' => 'Female',
        'name' => 'Female',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_gender_OptionValue_Male',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_gender_2', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_gender',
        'label' => E::ts('Male'),
        'value' => 'Male',
        'name' => 'Male',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_gender_OptionValue_Other',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_gender_3', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_gender',
        'label' => E::ts('Other'),
        'value' => 'Other',
        'name' => 'Other',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_gender_OptionValue_I_prefer_not_to_say',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_gender_4', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_gender',
        'label' => E::ts('I prefer not to say'),
        'value' => 'I prefer not to say',
        'name' => 'I_prefer_not_to_say',
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
