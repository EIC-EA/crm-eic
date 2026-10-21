<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_VentureMatch_Fields',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'VentureMatch_Fields',
        'title' => E::ts('VentureMatch-Fields'),
        'extends' => 'Organization',
        'extends_entity_column_value' => ['EIC_Awardee'],
        'weight' => 32,
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_eic_venturematch',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_Capacity',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'VentureMatch_Fields_Capacity',
        'title' => E::ts('VentureMatch-Fields :: Capacity'),
        'data_type' => 'String',
        'is_reserved' => FALSE,
        'option_value_fields' => ['name', 'label'],
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_Capacity_OptionValue_Low_Touch',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_Capacity',
        'label' => E::ts('Low Touch'),
        'value' => '1',
        'name' => 'Low_Touch',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_Capacity_OptionValue_Medium_Touch',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_Capacity',
        'label' => E::ts('Medium Touch'),
        'value' => '2',
        'name' => 'Medium_Touch',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_Capacity_OptionValue_High_Touch',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_Capacity',
        'label' => E::ts('High Touch'),
        'value' => '3',
        'name' => 'High_Touch',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_Capacity_OptionValue_Fast_Track',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_Capacity',
        'label' => E::ts('Fast Track'),
        'value' => '4',
        'name' => 'Fast_Track',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_VentureMatch_Fields_CustomField_Capacity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'VentureMatch_Fields',
        'name' => 'Capacity',
        'label' => E::ts('Capacity'),
        'data_type:name' => 'Alphanumeric',
        'html_type:name' => 'Drop-down (select list)',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'option_group_id.name' => 'VentureMatch_Fields_Capacity',
        'column_name' => 'capacity',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_C_Level_Team2',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'VentureMatch_Fields_C_Level_Team2',
        'title' => E::ts('VentureMatch-Fields :: C-Level/Team2'),
        'data_type' => 'Int',
        'is_reserved' => FALSE,
        'option_value_fields' => ['name', 'label'],
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_C_Level_Team2_OptionValue_c4ca4238a0b923820dcc509a6f75849',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'label' => E::ts('1'),
        'value' => '1',
        'name' => 'c4ca4238a0b923820dcc509a6f75849',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_C_Level_Team2_OptionValue_c81e728d9d4c2f636f067f89cc14862',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'label' => E::ts('2'),
        'value' => '2',
        'name' => 'c81e728d9d4c2f636f067f89cc14862',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_C_Level_Team2_OptionValue_eccbc87e4b5ce2fe28308fd9f2a7baf',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'label' => E::ts('3'),
        'value' => '3',
        'name' => 'eccbc87e4b5ce2fe28308fd9f2a7baf',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_C_Level_Team2_OptionValue_a87ff679a2f3e71d9181a67b7542122',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'label' => E::ts('4'),
        'value' => '4',
        'name' => 'a87ff679a2f3e71d9181a67b7542122',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_VentureMatch_Fields_C_Level_Team2_OptionValue_e4da3b7fbbce2345d7772b0674a318d',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'label' => E::ts('5'),
        'value' => '5',
        'name' => 'e4da3b7fbbce2345d7772b0674a318d',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_VentureMatch_Fields_CustomField_Pitch_Deck2',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'VentureMatch_Fields',
        'name' => 'Pitch_Deck2',
        'label' => E::ts('Pitch Deck'),
        'data_type:name' => 'Integer',
        'html_type:name' => 'Radio buttons',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'column_name' => 'pitch_deck'
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_VentureMatch_Fields_CustomField_C_Level_Team2',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'VentureMatch_Fields',
        'name' => 'C_Level_Team2',
        'label' => E::ts('C-Level/Team'),
        'data_type:name' => 'Integer',
        'html_type:name' => 'Radio buttons',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'column_name' => 'c_level'
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_VentureMatch_Fields_CustomField_Data_Room2',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'VentureMatch_Fields',
        'name' => 'Data_Room2',
        'label' => E::ts('Data Room'),
        'data_type:name' => 'Integer',
        'html_type:name' => 'Radio buttons',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'column_name' => 'data_room'
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_VentureMatch_Fields_CustomField_Financial_Model2',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'VentureMatch_Fields',
        'name' => 'Financial_Model2',
        'label' => E::ts('Financial Model'),
        'data_type:name' => 'Integer',
        'html_type:name' => 'Radio buttons',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'option_group_id.name' => 'VentureMatch_Fields_C_Level_Team2',
        'column_name' => 'financial_model'
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_VentureMatch_Fields_CustomField_Readiness_Score',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'VentureMatch_Fields',
        'name' => 'Readiness_Score',
        'label' => E::ts('Readiness Score'),
        'data_type:name' => 'Integer',
        'html_type:name' => 'Single-line input field (text or numeric)',
        'is_view' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'readiness_score'
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
