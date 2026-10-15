<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eic_vm_fundraising_round',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_fundraising_round',
        'title' => E::ts('VM Fundraising Round'),
        'description' => E::ts('Current fundraising round offered by the EIC VM Fundraising Assessment EU-Survey.'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_fundraising_round_OptionValue_Pre_Seed',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_fundraising_round',
        'label' => E::ts('Pre-Seed'),
        'value' => 'Pre-Seed',
        'name' => 'Pre_Seed',
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
    'name' => 'OptionGroup_eic_vm_fundraising_round_OptionValue_Seed',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_fundraising_round',
        'label' => E::ts('Seed'),
        'value' => 'Seed',
        'name' => 'Seed',
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
    'name' => 'OptionGroup_eic_vm_fundraising_round_OptionValue_Series_A',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_fundraising_round',
        'label' => E::ts('Series-A'),
        'value' => 'Series-A',
        'name' => 'Series_A',
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
    'name' => 'OptionGroup_eic_vm_fundraising_round_OptionValue_Series_B',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_fundraising_round',
        'label' => E::ts('Series-B'),
        'value' => 'Series-B',
        'name' => 'Series_B',
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
    'name' => 'OptionGroup_eic_vm_fundraising_round_OptionValue_Series_C',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_fundraising_round',
        'label' => E::ts('Series-C'),
        'value' => 'Series-C',
        'name' => 'Series_C',
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
    'name' => 'OptionGroup_eic_vm_fundraising_round_OptionValue_Series_D_',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_fundraising_round',
        'label' => E::ts('Series-D+'),
        'value' => 'Series-D+',
        'name' => 'Series_D_',
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
