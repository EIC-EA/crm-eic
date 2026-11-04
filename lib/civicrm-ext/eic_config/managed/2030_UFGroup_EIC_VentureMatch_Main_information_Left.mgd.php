<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'UFGroup_EIC_VentureMatch_Main_information_Left',
    'entity' => 'UFGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_VentureMatch_Main_information_Left',
        'group_type' => ['EIC_Awardee'],
        'title' => E::ts('EIC VentureMatch Main information'),
        'frontend_title' => E::ts('EIC VentureMatch Main information'),
        'is_update_dupe' => TRUE,
        'created_date' => '2026-10-22 12:48:25',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Main_information_Left_UFField_1',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Main_information_Left',
        'field_name' => 'custom_151',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Current Fundraising Round'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Main_information_Left_UFField_2',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Main_information_Left',
        'field_name' => 'custom_82',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Amount Currently Raising (EUR)'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Main_information_Left_UFField_3',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Main_information_Left',
        'field_name' => 'custom_83',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Current Runway (months)'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Main_information_Left_UFField_4',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Main_information_Left',
        'field_name' => 'custom_87',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Target Close Date for Current Round'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Main_information_Left_UFField_5',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Main_information_Left',
        'field_name' => 'custom_88',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Intended Use of Funds (current round)'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Main_information_Left_UFField_6',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Main_information_Left',
        'field_name' => 'custom_152',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey. Respondent selects one or two.'),
        'label' => E::ts('Priorities'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
];
