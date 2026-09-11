<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Business Readiness Level (BRL) options from the EU-Survey onboarding form.
// Option value = short code (e.g. "BRL 3"); label = full descriptive text.
// The survey transmits the full text, so the EU-Survey import resolves the
// full label to the short code before storing.
$levels = [
  'BRL 1' => 'BRL 1 - No or unclear description of business idea, market potential and competition',
  'BRL 2' => 'BRL 2 - Description of possible business concept, market opportunity and competition. Some insight into sustainability aspects of business',
  'BRL 3' => 'BRL 3 - Description of business model, target market(s) and competitive landscape',
  'BRL 4' => 'BRL 4 - First calculations indicate economically viable business model. First sustainability assessment of proposed business model',
  'BRL 5' => 'BRL 5 - Market feedback on key assumptions of business model',
  'BRL 6' => 'BRL 6 - Business model validated by target customers (pilot/test sales). Key sustainability metrics proposed',
  'BRL 7' => 'BRL 7 - Business model validated by commercial sales',
  'BRL 8' => 'BRL 8 - Sales and metrics show that business model is viable. Sustainability integrated and used to create business value',
  'BRL 9' => 'BRL 9 - Business model proven to meet expectations on profit, growth and sustainability',
];

$entities = [
  [
    'name' => 'OptionGroup_eu_survey_brl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_brl',
        'title' => E::ts('EU-Survey Business Readiness Level (BRL)'),
        'description' => E::ts('Business Readiness Level options from the EU-Survey beneficiary onboarding form.'),
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
    'name' => 'OptionValue_eu_survey_brl_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_brl',
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
