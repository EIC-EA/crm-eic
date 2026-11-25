<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_1',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'Spin-off of',
        'label_a_b' => E::ts('Spin-off of'),
        'name_b_a' => 'is Spin-off',
        'label_b_a' => E::ts('is Spin-off Parent'),
        'description' => E::ts('An additional contact for the organisation that is a spin-off of a EIC Awardee organisation.'),
        'contact_type_a' => 'Organization',
        'contact_type_b' => 'Organization',
        'contact_sub_type_a' => 'Spin_off',
        'contact_sub_type_b' => 'EIC_Awardee',
        'weight' => 12,
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
