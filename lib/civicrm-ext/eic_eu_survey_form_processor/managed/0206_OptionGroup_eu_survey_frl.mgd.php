<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eu_survey_frl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_frl',
        'title' => E::ts('EU-Survey Funding Readiness Level (FRL)'),
        'description' => E::ts('Funding Readiness Level options from the EU-Survey beneficiary onboarding form.'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_1_Little_insight_into_fundi',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_1', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 1 - Little insight into funding needs and funding options. No funding or plan for validation of idea'),
        'value' => 'FRL 1',
        'name' => 'FRL_1_Little_insight_into_fundi',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_2_Identified_funding_needs_',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_2', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 2 - Identified funding needs and funding options for validation. Initiated efforts to secure funding for validation'),
        'value' => 'FRL 2',
        'name' => 'FRL_2_Identified_funding_needs_',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_3_Insight_into_overall_fund',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_3', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 3 - Insight into overall funding Options and their requirements. Secured initial funding for validation'),
        'value' => 'FRL 3',
        'name' => 'FRL_3_Insight_into_overall_fund',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_4_Pitch_presentation_in_pla',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_4', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 4 - Pitch/presentation in place for next-stage funding. Secured funding to initiate development'),
        'value' => 'FRL 4',
        'name' => 'FRL_4_Pitch_presentation_in_pla',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_5_Pitch_for_funding_tested_',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_5', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 5 - Pitch for funding tested on relevant audience. Possible funding roadmap and first financial projections'),
        'value' => 'FRL 5',
        'name' => 'FRL_5_Pitch_for_funding_tested_',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_6_Improved_Pitch_for_fundin',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_6', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 6 - Improved Pitch for funding based on feedback. Discussions with relevant funding sources'),
        'value' => 'FRL 6',
        'name' => 'FRL_6_Improved_Pitch_for_fundin',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_7_Term_sheet_level_discussi',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_7', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 7 - Term sheet level discussions with funding source(s). All material in place to pass a due diligence for funding'),
        'value' => 'FRL 7',
        'name' => 'FRL_7_Term_sheet_level_discussi',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_8_Secured_funding_for_at_le',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_8', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 8 - Secured funding for at least 12 months of operations. Control of financial Status via financial monitoring and forecasting'),
        'value' => 'FRL 8',
        'name' => 'FRL_8_Secured_funding_for_at_le',
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
    'name' => 'OptionGroup_eu_survey_frl_OptionValue_FRL_9_Long_term_funding_strateg',
    'entity' => 'OptionValue',
    'replaces' => ['module' => 'eic_eu_survey_form_processor', 'name' => 'OptionValue_eu_survey_frl_9', 'entity' => 'OptionValue'],
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
        'label' => E::ts('FRL 9 - Long term funding strategy in place. Prepared next Needed funding with interest from funding sources'),
        'value' => 'FRL 9',
        'name' => 'FRL_9_Long_term_funding_strateg',
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
