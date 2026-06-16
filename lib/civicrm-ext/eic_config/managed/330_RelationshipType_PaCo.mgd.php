<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_PaCo',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'EIC_PaCo_For',
        'label_a_b' => E::ts('PaCo - EIC Project Participant Contact for'),
        'name_b_a' => 'EIC_PaCo_Is',
        'label_b_a' => E::ts('PaCo - EIC Project Participant Contact is'),
        'description' => E::ts('The Participant Contact (PaCo) is a representative of an organisation in the consortium that is not the coordinating organisation. An organisation can have an unlimited number of PaCos per project.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
        'contact_sub_type_a' => 'EIC_Registered',
        'contact_sub_type_b' => 'EIC_Awardee',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
