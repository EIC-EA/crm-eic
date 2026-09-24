<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Technology Readiness Level (TRL) options from the EU-Survey onboarding form.
// Option value = short code (e.g. "TRL 4"); label = full descriptive text.
// The survey transmits the full text, so the EU-Survey import resolves the
// full label to the short code before storing.
$levels = [
  'TRL 1' => 'TRL 1 - Interesting research results or initial technology idea identified',
  'TRL 2' => 'TRL 2 - Technology concept and/or application formulated',
  'TRL 3' => 'TRL 3 - Proof-of-concept of critical functions and/or characteristics in laboratory',
  'TRL 4' => 'TRL 4 - Technology validation in laboratory',
  'TRL 5' => 'TRL 5 - Technology validation in relevant environment',
  'TRL 6' => 'TRL 6 - Technology prototype demonstration in relevant environment',
  'TRL 7' => 'TRL 7 - Technology prototype demonstration in operational environment',
  'TRL 8' => 'TRL 8 - Technology complete and demonstrated in actual operations',
  'TRL 9' => 'TRL 9 - Technology complete and proven in actual operations over time',
];

$entities = [
  [
    'name' => 'OptionGroup_eu_survey_trl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_trl',
        'title' => E::ts('EU-Survey Technology Readiness Level (TRL)'),
        'description' => E::ts('Technology Readiness Level options from the EU-Survey beneficiary onboarding form.'),
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
];

$weight = 1;
foreach ($levels as $code => $label) {
  $entities[] = [
    'name' => 'OptionValue_eu_survey_trl_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_trl',
        'label' => E::ts($label),
        'value' => $code,
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
