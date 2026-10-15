<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_eic_vm_sector_cluster',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_sector_cluster',
        'title' => E::ts('VM Sector / Industry Cluster'),
        'description' => E::ts('Primary Sector/Industry Cluster offered by the EIC VM Fundraising Assessment EU-Survey. Distinct from the EU-Survey onboarding "Sector".'),
        'option_value_fields' => ['name', 'label', 'description'],
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_Food_Agritech',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('Food & Agritech'),
        'value' => 'Food & Agritech',
        'name' => 'Food_Agritech',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_Energy_Transition',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('Energy Transition'),
        'value' => 'Energy Transition',
        'name' => 'Energy_Transition',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_Transport_Mobility',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('Transport & Mobility'),
        'value' => 'Transport & Mobility',
        'name' => 'Transport_Mobility',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_Space',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('Space'),
        'value' => 'Space',
        'name' => 'Space',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_New_Materials_Industrial',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('New Materials & Industrial'),
        'value' => 'New Materials & Industrial',
        'name' => 'New_Materials_Industrial',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_AI_Software',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('AI & Software'),
        'value' => 'AI & Software',
        'name' => 'AI_Software',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_Semicon_Quantum',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('Semicon & Quantum'),
        'value' => 'Semicon & Quantum',
        'name' => 'Semicon_Quantum',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_Dual_Use_Defense',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('Dual Use & Defense'),
        'value' => 'Dual Use & Defense',
        'name' => 'Dual_Use_Defense',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_Biotechnology',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('Biotechnology'),
        'value' => 'Biotechnology',
        'name' => 'Biotechnology',
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
    'name' => 'OptionGroup_eic_vm_sector_cluster_OptionValue_MedTech',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts('MedTech'),
        'value' => 'MedTech',
        'name' => 'MedTech',
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
