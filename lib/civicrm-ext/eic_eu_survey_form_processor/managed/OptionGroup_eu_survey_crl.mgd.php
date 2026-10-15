<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eu_survey_crl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_crl',
        'title' => E::ts('EU-Survey Commercial Readiness Level (CRL)'),
        'description' => E::ts('Commercial Readiness Level options from the EU-Survey beneficiary onboarding form.'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_1_Hypothesis_of_possible_ne',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 1 - Hypothesis of possible needs in the market'),
        'value' => 'CRL 1',
        'name' => 'CRL_1_Hypothesis_of_possible_ne',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_2_Identified_specific_needs',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 2 - Identified specific needs in market'),
        'value' => 'CRL 2',
        'name' => 'CRL_2_Identified_specific_needs',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_3_First_market_feedback_est',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 3 - First market feedback established'),
        'value' => 'CRL 3',
        'name' => 'CRL_3_First_market_feedback_est',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_4_Confirmed_problem_needs_f',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 4 - Confirmed problem/needs from several customers or users'),
        'value' => 'CRL 4',
        'name' => 'CRL_4_Confirmed_problem_needs_f',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_5_Established_interest_and_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 5 - Established interest and relations with customers'),
        'value' => 'CRL 5',
        'name' => 'CRL_5_Established_interest_and_',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_6_Benefits_confirmed_by_fir',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 6 - Benefits confirmed by first customer testing'),
        'value' => 'CRL 6',
        'name' => 'CRL_6_Benefits_confirmed_by_fir',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_7_Customers_in_extended_tes',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 7 - Customers in extended testing or first test sales. Small number of active users'),
        'value' => 'CRL 7',
        'name' => 'CRL_7_Customers_in_extended_tes',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_8_First_commercial_sales_an',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 8 - First commercial sales and implemented sales process. Substantial number of active users'),
        'value' => 'CRL 8',
        'name' => 'CRL_8_First_commercial_sales_an',
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
    'name' => 'OptionGroup_eu_survey_crl_OptionValue_CRL_9_Widespread_sales_that_sca',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
        'label' => E::ts('CRL 9 - Widespread sales that scale. Large number of active users with substantial growth'),
        'value' => 'CRL 9',
        'name' => 'CRL_9_Widespread_sales_that_sca',
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
