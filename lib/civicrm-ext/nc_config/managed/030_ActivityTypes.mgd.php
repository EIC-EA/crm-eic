<?php

use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'OptionValue_Outreach_email',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Outreach email'),
        'value' => '67',
        'name' => 'Outreach email',
        'weight' => 68,
        'description' => E::ts('<p>Outreach email</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-envelope',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Outreach_response',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Outreach response'),
        'value' => '68',
        'name' => 'Outreach response',
        'weight' => 69,
        'description' => E::ts('<p>Outreach response</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-reply',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Onboarding_call',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Onboarding call'),
        'value' => '69',
        'name' => 'Onboarding call',
        'weight' => 70,
        'description' => E::ts('<p>Onboarding call</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-phone-flip',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Onboarding_Survey_B',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Onboarding Survey B'),
        'value' => '70',
        'name' => 'Onboarding Survey B',
        'weight' => 71,
        'description' => E::ts('<p>Onboarding Survey B</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-file-lines',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Introduction_request',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Introduction request'),
        'value' => '73',
        'name' => 'Introduction request',
        'weight' => 72,
        'description' => E::ts('<p>Introduction request</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-code-pull-request',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Introduction_response',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'activity_type',
        'label' => E::ts('Introduction response'),
        'value' => '74',
        'name' => 'Introduction response',
        'weight' => 73,
        'description' => E::ts('<p>Introduction response</p>'),
        'component_id:name' => 'CiviCase',
        'icon' => 'fa-reply-all',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
