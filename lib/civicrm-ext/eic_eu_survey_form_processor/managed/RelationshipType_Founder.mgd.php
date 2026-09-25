<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// "Founder" relationship type. Links the founder (Individual) to the beneficiary
// organisation. Used as a case role on the VentureMatch Service Request case: the
// Founder's Primary Contact from the EIC VM Fundraising Assessment EU-Survey is
// linked to the case in this role.
//
// update=always so it re-applies on every reconcile and stays identical across
// environments.

return [
  [
    'name' => 'RelationshipType_Founder',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'Founder of',
        'label_a_b' => E::ts('Founder of'),
        'name_b_a' => 'Founder is',
        'label_b_a' => E::ts('Founder is'),
        'description' => E::ts('The founder of the organisation. Communicated by the beneficiary in the EIC VM Fundraising Assessment EU-Survey (Founder\'s Primary Contact), and used as a role on the VentureMatch Service Request case.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
