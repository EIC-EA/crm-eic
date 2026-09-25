<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_eic_accelerator_onboarding_survey',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('EIC Accelerator Onboarding Survey Data'),
        'name' => 'eic_accelerator_onboarding_survey',
        'weight' => 117,
        'description' => E::ts('<p>represents eu-survey data</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-file-text-o',
      ],
      'match' => [
        'option_group_id.name',
        'name',
      ],
    ],
  ],
];
