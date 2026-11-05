<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Snapshot',
    'entity' => 'UFGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_VentureMatch_Fundraising_Snapshot',
        'group_type' => ['EIC_Awardee'],
        'title' => E::ts('EIC VentureMatch Fundraising Snapshot'),
        'frontend_title' => E::ts('EIC VentureMatch Fundraising Snapshot'),
        'is_update_dupe' => TRUE,
        'created_date' => '2026-10-28 15:59:25',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Snapshot_UFField_1',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Fundraising_Snapshot',
        'field_name' => 'custom_84',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Have you raised capital before?'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Snapshot_UFField_2',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Fundraising_Snapshot',
        'field_name' => 'custom_85',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey. VCs, Angels, Corporate VCs, etc. - separated by ";".'),
        'label' => E::ts('List Current Key Investors'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_EIC_VentureMatch_Fundraising_Snapshot_UFField_3',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'EIC_VentureMatch_Fundraising_Snapshot',
        'field_name' => 'custom_86',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Total Amount Raised to Date (EUR)'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
];
