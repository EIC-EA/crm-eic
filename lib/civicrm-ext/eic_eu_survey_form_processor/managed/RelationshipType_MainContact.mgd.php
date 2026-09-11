<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_MainContact',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'Main contact for',
        'label_a_b' => E::ts('Main contact for'),
        'name_b_a' => 'Main contact is',
        'label_b_a' => E::ts('Main contact is'),
        'description' => E::ts('This is the main contact communicated by the beneficiary during the EU-Survey onboarding. It links the primary contact person (Section 2 of the onboarding form) to the beneficiary organisation.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
