<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eu_survey_brl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_brl',
        'title' => E::ts('EU-Survey Business Readiness Level (BRL)'),
        'description' => E::ts('Business Readiness Level options from the EU-Survey beneficiary onboarding form.'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_1_No_or_unclear_description',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 1 - No or unclear description of business idea, market potential and competition'),
        'value' => 'BRL 1',
        'name' => 'BRL_1_No_or_unclear_description',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_2_Description_of_possible_b',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 2 - Description of possible business concept, market opportunity and competition. Some insight into sustainability aspects of business'),
        'value' => 'BRL 2',
        'name' => 'BRL_2_Description_of_possible_b',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_3_Description_of_business_m',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 3 - Description of business model, target market(s) and competitive landscape'),
        'value' => 'BRL 3',
        'name' => 'BRL_3_Description_of_business_m',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_4_First_calculations_indica',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 4 - First calculations indicate economically viable business model. First sustainability assessment of proposed business model'),
        'value' => 'BRL 4',
        'name' => 'BRL_4_First_calculations_indica',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_5_Market_feedback_on_key_as',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 5 - Market feedback on key assumptions of business model'),
        'value' => 'BRL 5',
        'name' => 'BRL_5_Market_feedback_on_key_as',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_6_Business_model_validated_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 6 - Business model validated by target customers (pilot/test sales). Key sustainability metrics proposed'),
        'value' => 'BRL 6',
        'name' => 'BRL_6_Business_model_validated_',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_7_Business_model_validated_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 7 - Business model validated by commercial sales'),
        'value' => 'BRL 7',
        'name' => 'BRL_7_Business_model_validated_',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_8_Sales_and_metrics_show_th',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 8 - Sales and metrics show that business model is viable. Sustainability integrated and used to create business value'),
        'value' => 'BRL 8',
        'name' => 'BRL_8_Sales_and_metrics_show_th',
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
    'name' => 'OptionGroup_eu_survey_brl_OptionValue_BRL_9_Business_model_proven_to_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
        'label' => E::ts('BRL 9 - Business model proven to meet expectations on profit, growth and sustainability'),
        'value' => 'BRL 9',
        'name' => 'BRL_9_Business_model_proven_to_',
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
