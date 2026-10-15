<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eu_survey_trl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_trl',
        'title' => E::ts('EU-Survey Technology Readiness Level (TRL)'),
        'description' => E::ts('Technology Readiness Level options from the EU-Survey beneficiary onboarding form.'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_1_Interesting_research_resu',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 1 - Interesting research results or initial technology idea identified'),
        'value' => 'TRL 1',
        'name' => 'TRL_1_Interesting_research_resu',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_2_Technology_concept_and_or',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 2 - Technology concept and/or application formulated'),
        'value' => 'TRL 2',
        'name' => 'TRL_2_Technology_concept_and_or',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_3_Proof_of_concept_of_criti',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 3 - Proof-of-concept of critical functions and/or characteristics in laboratory'),
        'value' => 'TRL 3',
        'name' => 'TRL_3_Proof_of_concept_of_criti',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_4_Technology_validation_in_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 4 - Technology validation in laboratory'),
        'value' => 'TRL 4',
        'name' => 'TRL_4_Technology_validation_in_',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_5_Technology_validation_in_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 5 - Technology validation in relevant environment'),
        'value' => 'TRL 5',
        'name' => 'TRL_5_Technology_validation_in_',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_6_Technology_prototype_demo',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 6 - Technology prototype demonstration in relevant environment'),
        'value' => 'TRL 6',
        'name' => 'TRL_6_Technology_prototype_demo',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_7_Technology_prototype_demo',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 7 - Technology prototype demonstration in operational environment'),
        'value' => 'TRL 7',
        'name' => 'TRL_7_Technology_prototype_demo',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_8_Technology_complete_and_d',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 8 - Technology complete and demonstrated in actual operations'),
        'value' => 'TRL 8',
        'name' => 'TRL_8_Technology_complete_and_d',
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
    'name' => 'OptionGroup_eu_survey_trl_OptionValue_TRL_9_Technology_complete_and_p',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts('TRL 9 - Technology complete and proven in actual operations over time'),
        'value' => 'TRL 9',
        'name' => 'TRL_9_Technology_complete_and_p',
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
