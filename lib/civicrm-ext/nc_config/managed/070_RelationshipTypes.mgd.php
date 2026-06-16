<?php

use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_Investor_At',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'Investor at',
        'label_a_b' => E::ts('Investor at'),
        'name_b_a' => 'Investor',
        'label_b_a' => E::ts('Investor'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
        'contact_sub_type_a' => 'Investor_Representative',
        'contact_sub_type_b' => 'Investor',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
