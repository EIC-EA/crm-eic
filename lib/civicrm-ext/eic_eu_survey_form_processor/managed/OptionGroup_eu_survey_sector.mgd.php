<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Sectors offered by the EU-Survey onboarding form.
// Option values are stored equal to their labels so that the value transmitted
// by the survey (the label text) can be imported directly without resolution.
$sectors = [
  'Agriculture & Food',
  'Energy',
  'Climate & Environmental Tech',
  'Built Environment',
  'Mobility',
  'Health Biotechnology',
  'Medical Technologies',
  'Space',
  'Advanced Manufacturing & Advanced Materials',
  'AI, Data & ICT',
  'Quantum, Advanced Computing & Semiconductors',
  'Social Innovation, Culture & Creative Industries',
];

$entities = [
  [
    'name' => 'OptionGroup_eu_survey_sector',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_sector',
        'title' => E::ts('EU-Survey Sector'),
        'description' => E::ts('Sectors offered by the EU-Survey beneficiary onboarding form.'),
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
];

$weight = 1;
foreach ($sectors as $sector) {
  $entities[] = [
    'name' => 'OptionValue_eu_survey_sector_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_sector',
        'label' => E::ts($sector),
        'value' => $sector,
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
