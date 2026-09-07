<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_eu_survey',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('EU-Survey Data'),
        'value' => '67',
        'name' => 'eu_survey',
        'weight' => 117,
        'description' => E::ts('<p>represents eu-survey data</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-file-text-o',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
