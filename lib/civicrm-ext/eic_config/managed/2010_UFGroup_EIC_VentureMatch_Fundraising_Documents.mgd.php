<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Documents',
    'entity' => 'UFGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_VentureMatch_Fundraising_Documents',
        'group_type' => ['EIC_Awardee'],
        'title' => E::ts('EIC VentureMatch Fundraising Documents'),
        'frontend_title' => E::ts('EIC VentureMatch Fundraising Documents'),
        'is_update_dupe' => TRUE,
        'created_date' => '2026-10-28 16:05:58',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Documents_UFField_1',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Fundraising_Documents',
        'field_name' => 'custom_89',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Link to Pitchdeck'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Documents_UFField_2',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Fundraising_Documents',
        'field_name' => 'custom_90',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Existing Metrics / Financial Model Link'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Documents_UFField_3',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Fundraising_Documents',
        'field_name' => 'custom_91',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Existing Metrics / Data Room Link'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
];
