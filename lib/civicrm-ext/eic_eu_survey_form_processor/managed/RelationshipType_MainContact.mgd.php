<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_MainContact',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'Main contact for',
        'label_a_b' => E::ts('Main EIC BAS Contact for'),
        'name_b_a' => 'Main contact is',
        'label_b_a' => E::ts('Main EIC BAS Contact is'),
        'description' => E::ts('The main contact for the organisation regarding EIC Business Acceleration Services (BAS). Usually communicated by the beneficiary in Section 2 of the EU-Survey onboarding form, but can also be added manually. Links the primary contact person to the beneficiary organisation; the contact\'s declared role is stored on the Individual\'s job title.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
