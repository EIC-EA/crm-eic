<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Funding Readiness Level (FRL) options from the EU-Survey onboarding form.
// Option value = short code (e.g. "FRL 7"); label = full descriptive text.
// The survey transmits the full text, so the EU-Survey import resolves the
// full label to the short code before storing.
$levels = [
  'FRL 1' => 'FRL 1 - Little insight into funding needs and funding options. No funding or plan for validation of idea',
  'FRL 2' => 'FRL 2 - Identified funding needs and funding options for validation. Initiated efforts to secure funding for validation',
  'FRL 3' => 'FRL 3 - Insight into overall funding Options and their requirements. Secured initial funding for validation',
  'FRL 4' => 'FRL 4 - Pitch/presentation in place for next-stage funding. Secured funding to initiate development',
  'FRL 5' => 'FRL 5 - Pitch for funding tested on relevant audience. Possible funding roadmap and first financial projections',
  'FRL 6' => 'FRL 6 - Improved Pitch for funding based on feedback. Discussions with relevant funding sources',
  'FRL 7' => 'FRL 7 - Term sheet level discussions with funding source(s). All material in place to pass a due diligence for funding',
  'FRL 8' => 'FRL 8 - Secured funding for at least 12 months of operations. Control of financial Status via financial monitoring and forecasting',
  'FRL 9' => 'FRL 9 - Long term funding strategy in place. Prepared next Needed funding with interest from funding sources',
];

$entities = [
  [
    'name' => 'OptionGroup_eu_survey_frl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_frl',
        'title' => E::ts('EU-Survey Funding Readiness Level (FRL)'),
        'description' => E::ts('Funding Readiness Level options from the EU-Survey beneficiary onboarding form.'),
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
    'name' => 'OptionValue_eu_survey_frl_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_frl',
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
