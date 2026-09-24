<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_Contact',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'Contact for',
        'label_a_b' => E::ts('EIC BAS Contact for'),
        'name_b_a' => 'Contact is',
        'label_b_a' => E::ts('EIC BAS Contact is'),
        'description' => E::ts('An additional contact for the organisation regarding EIC Business Acceleration Services (BAS). Usually communicated by the beneficiary in the EU-Survey onboarding form (which allows up to three additional contacts, contacts 2 to 4, beyond the main contact), but can also be added manually. Links a contact person to the beneficiary organisation; the contact\'s declared role is stored on the Individual\'s job title.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
