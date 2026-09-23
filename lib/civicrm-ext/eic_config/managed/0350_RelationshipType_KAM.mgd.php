<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_KAM',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'KAM for',
        'label_a_b' => E::ts('KAM for'),
        'name_b_a' => 'KAM is',
        'label_b_a' => E::ts('KAM is'),
        'description' => E::ts('Key Account Manager (KAM) responsible for the organisation.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
