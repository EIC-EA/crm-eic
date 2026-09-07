<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eu_survey_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_data',
        'title' => E::ts('EU-Survey Data'),
        'extends' => 'Activity',
        'extends_entity_column_value' => ['67'],
        'collapse_adv_display' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_EIC_Project_Acronym',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'EIC_Project_Acronym',
        'label' => E::ts('EIC Project Acronym'),
        'html_type' => 'Text',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_EIC_Project_ID',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'EIC_Project_ID',
        'label' => E::ts('EIC Project ID'),
        'html_type' => 'Text',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_case_latest_pitch_deck',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'case_latest_pitch_deck',
        'label' => E::ts('Pitch Deck'),
        'html_type' => 'File',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_fundraising_within_the_next_18_months',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_fundraising_within_the_next_18_months',
        'label' => E::ts('Are you fundraising within the next 18 months'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_fundraising_within',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_fundraising_within',
        'label' => E::ts('Are you fundraising within:'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_suport_fundraising_items',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_suport_fundraising_items',
        'label' => E::ts('What fundraising support do you need most'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_support_investment',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_support_investment',
        'label' => E::ts('Would you like to receive investment support'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_partenrs_actively_seeking',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_partenrs_actively_seeking',
        'label' => E::ts('Are you actively seeking corporate or industrial partners'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_partners_types',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_partners_types',
        'label' => E::ts('What type of partner are you targeting'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_selling_to_public_private_buyers',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_selling_to_public_private_buyers',
        'label' => E::ts('Are you planning to sell your innovative solution to public or private buyers'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_selling_to_public_private_buyers_where_are_you_in_the_',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_selling_to_public_private_buyers_where_are_you_in_the_...',
        'label' => E::ts('Where are you in the process'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_support_procurement',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_support_procurement',
        'label' => E::ts('Would you like procurement support or training'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_international_expansion_in_the_next_18_months',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_international_expansion_in_the_next_18_months',
        'label' => E::ts('Are you planning to expand internationally in the next 18 months'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_international_expansion_markets',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_international_expansion_markets',
        'label' => E::ts('Which markets'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_international_expansion_main_barrier',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_international_expansion_main_barrier',
        'label' => E::ts('What is your main barrier'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_international_trade_fairs_participate_in_the_next_12_m_',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_international_trade_fairs_participate_in_the_next_12_m...',
        'label' => E::ts('Are you planning to participate in International trade fairs in the next 12 months'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_international_trade_fairs_region',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_international_trade_fairs_region',
        'label' => E::ts('Which region'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_international_trade_fairs_events',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_international_trade_fairs_events',
        'label' => E::ts('Type of events'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_support_industry_experts_or_coaches',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_support_industry_experts_or_coaches',
        'label' => E::ts('Would you benefit from industry experts or coaches support'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_support_industry_experts_or_coaches_types',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_support_industry_experts_or_coaches_types',
        'label' => E::ts('What type of support'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_main_challenge',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_main_challenge',
        'label' => E::ts('What is your main challenge?'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_Does_your_company_have_a_woman_founder_or_executive_wh_',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_Does_your_company_have_a_woman_founder_or_executive_wh...',
        'label' => E::ts('Does your company have a woman founder or executive who would benefit from a dedicated leadership programme'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_support_other_types',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_support_other_types',
        'label' => E::ts('What type of other support are you interested in'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_support_other_items',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_support_other_items',
        'label' => E::ts('Are there any other support items you would like to benefit from'),
        'html_type' => 'Text',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_feedback',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_feedback',
        'label' => E::ts('General feedback'),
        'html_type' => 'TextArea',
        'attributes' => 'rows=4, cols=60',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eu_survey_data_CustomField_survey_receive_support_entering_new_markets',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eu_survey_data',
        'name' => 'survey_receive_support_entering_new_markets',
        'label' => E::ts('Would you like to receive support entering new markets'),
        'data_type' => 'Boolean',
        'html_type' => 'Toggle',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
