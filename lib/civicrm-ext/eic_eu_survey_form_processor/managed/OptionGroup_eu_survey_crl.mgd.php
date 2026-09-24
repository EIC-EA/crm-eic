<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Commercial Readiness Level (CRL) options from the EU-Survey onboarding form.
// Option value = short code (e.g. "CRL 4"); label = full descriptive text.
// The survey transmits the full text, so the EU-Survey import resolves the
// full label to the short code before storing.
$levels = [
  'CRL 1' => 'CRL 1 - Hypothesis of possible needs in the market',
  'CRL 2' => 'CRL 2 - Identified specific needs in market',
  'CRL 3' => 'CRL 3 - First market feedback established',
  'CRL 4' => 'CRL 4 - Confirmed problem/needs from several customers or users',
  'CRL 5' => 'CRL 5 - Established interest and relations with customers',
  'CRL 6' => 'CRL 6 - Benefits confirmed by first customer testing',
  'CRL 7' => 'CRL 7 - Customers in extended testing or first test sales. Small number of active users',
  'CRL 8' => 'CRL 8 - First commercial sales and implemented sales process. Substantial number of active users',
  'CRL 9' => 'CRL 9 - Widespread sales that scale. Large number of active users with substantial growth',
];

$entities = [
  [
    'name' => 'OptionGroup_eu_survey_crl',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_crl',
        'title' => E::ts('EU-Survey Commercial Readiness Level (CRL)'),
        'description' => E::ts('Commercial Readiness Level options from the EU-Survey beneficiary onboarding form.'),
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
    'name' => 'OptionValue_eu_survey_crl_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_crl',
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
