<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_EIC_Project_member',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'EIC Project member of',
        'label_a_b' => E::ts('EIC Other Project member for'),
        'name_b_a' => 'EIC Project member is',
        'label_b_a' => E::ts('EIC Other Project member is'),
        'description' => E::ts('Is part of an EIC project with another role than PCoCo, CoCo or PaCo'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
        'contact_sub_type_a' => 'EIC_Registered',
        'contact_sub_type_b' => 'EIC_Awardee',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
