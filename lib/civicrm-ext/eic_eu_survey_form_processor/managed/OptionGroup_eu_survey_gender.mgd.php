<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Gender options offered by the EU-Survey onboarding form.
// These mirror the standard CiviCRM gender labels but are kept as a dedicated
// option group with value = label, so the value transmitted by the survey (the
// label text) can be imported directly without any label-to-value resolution.
$genders = [
  'Female',
  'Male',
  'Other',
  'I prefer not to say',
];

$entities = [
  [
    'name' => 'OptionGroup_eu_survey_gender',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_gender',
        'title' => E::ts('EU-Survey Gender'),
        'description' => E::ts('Gender options offered by the EU-Survey beneficiary onboarding form.'),
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
];

$weight = 1;
foreach ($genders as $gender) {
  $entities[] = [
    'name' => 'OptionValue_eu_survey_gender_' . $weight,
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'eu_survey_gender',
        'label' => E::ts($gender),
        'value' => $gender,
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
