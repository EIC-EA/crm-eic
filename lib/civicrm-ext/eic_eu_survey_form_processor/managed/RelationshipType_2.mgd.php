<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_2',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'Contact for',
        'label_a_b' => E::ts('Contact for'),
        'name_b_a' => 'Contact is',
        'label_b_a' => E::ts('Contact is'),
        'description' => E::ts('An additional contact communicated by the beneficiary during the EU-Survey onboarding. The onboarding form allows up to three additional contacts (contacts 2 to 4) beyond the main contact. It links a contact person to the beneficiary organisation.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
