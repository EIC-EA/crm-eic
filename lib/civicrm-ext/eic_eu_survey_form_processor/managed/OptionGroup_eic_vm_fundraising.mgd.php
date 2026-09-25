<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Option groups used by the EIC VM Fundraising Assessment EU-Survey import.
// Option values are stored equal to their labels so the value transmitted by the
// survey (the label text) can be imported directly without resolution.
//
//   - eic_vm_sector_cluster : Primary Sector/Industry Cluster (single select)
//   - eic_vm_priorities     : Priorities (multi-select, choose 1-2)
//
// Both are managed with update=always so they re-apply on every reconcile and
// stay identical across environments.

$sectorCluster = [
  'Food & Agritech',
  'Energy Transition',
  'Transport & Mobility',
  'Space',
  'New Materials & Industrial',
  'AI & Software',
  'Semicon & Quantum',
  'Dual Use & Defense',
  'Biotechnology',
  'MedTech',
];

$priorities = [
  'Product Development / Technical Milestones',
  'Customer Acquisition / Sales Growth',
  'Team Hiring / Talent Acquisition',
  'Securing Key Partnerships',
  'Extending the Cash Runway',
  'Optimizing Corporate Model / Unit Economics',
  'IP Protection / Filing',
  'Regulatory Compliance / Certification',
  'Financial Planning / Forecasting',
];

$fundraisingRound = [
  'Pre-Seed',
  'Seed',
  'Series-A',
  'Series-B',
  'Series-C',
  'Series-D+',
];

$entities = [
  [
    'name' => 'OptionGroup_eic_vm_sector_cluster',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_sector_cluster',
        'title' => E::ts('VM Sector / Industry Cluster'),
        'description' => E::ts('Primary Sector/Industry Cluster offered by the EIC VM Fundraising Assessment EU-Survey. Distinct from the EU-Survey onboarding "Sector".'),
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_priorities',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_priorities',
        'title' => E::ts('VM Fundraising Priorities'),
        'description' => E::ts('Priorities offered by the EIC VM Fundraising Assessment EU-Survey (respondent selects one or two).'),
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_eic_vm_fundraising_round',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_fundraising_round',
        'title' => E::ts('VM Fundraising Round'),
        'description' => E::ts('Current fundraising round offered by the EIC VM Fundraising Assessment EU-Survey.'),
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
];

$weight = 1;
foreach ($sectorCluster as $value) {
  $entities[] = [
    'name' => 'OptionValue_eic_vm_sector_cluster_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_sector_cluster',
        'label' => E::ts($value),
        'value' => $value,
        'weight' => $weight,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'value',
      ],
    ],
  ];
  $weight++;
}

$weight = 1;
foreach ($priorities as $value) {
  $entities[] = [
    'name' => 'OptionValue_eic_vm_priorities_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_priorities',
        'label' => E::ts($value),
        'value' => $value,
        'weight' => $weight,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'value',
      ],
    ],
  ];
  $weight++;
}

$weight = 1;
foreach ($fundraisingRound as $value) {
  $entities[] = [
    'name' => 'OptionValue_eic_vm_fundraising_round_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eic_vm_fundraising_round',
        'label' => E::ts($value),
        'value' => $value,
        'weight' => $weight,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'value',
      ],
    ],
  ];
  $weight++;
}

return $entities;
