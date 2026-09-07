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
        'name_a_b' => 'Applicant for',
        'label_a_b' => E::ts('Applicant for'),
        'name_b_a' => 'Applicant is',
        'label_b_a' => E::ts('Applicant is'),
        'description' => E::ts('Person submitting an application'),
        'contact_type_a' => 'Individual',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
