<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_spin_off_plan',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'spin_off_plan',
        'title' => E::ts('Spin-off Plan'),
        'data_type' => 'Integer',
        'is_reserved' => FALSE,
        'option_value_fields' => ['name', 'label', 'description'],
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'OptionGroup_spin_off_plan_OptionValue_Yes_spin_off_already_created',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'spin_off_plan',
        'label' => E::ts('Yes - spin-off already created'),
        'value' => '1',
        'name' => 'Yes - spin-off already created',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_spin_off_plan_OptionValue_es_planning_to_create_one_during_or_after_the_project',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'spin_off_plan',
        'label' => E::ts('Yes - planning to create one during or after the project'),
        'value' => '2',
        'name' => 'es - planning to create one during or after the project',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_spin_off_plan_OptionValue_A_company_from_the_consortium_or_external_will_commercialise_the_project_s_results',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'spin_off_plan',
        'label' => E::ts('A company (from the consortium or external) will commercialise the project’s results'),
        'value' => '3',
        'name' => 'A company (from the consortium or external) will commercialise the project’s results',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_spin_off_plan_OptionValue_No',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'spin_off_plan',
        'label' => E::ts('No'),
        'value' => '4',
        'name' => 'No',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];
