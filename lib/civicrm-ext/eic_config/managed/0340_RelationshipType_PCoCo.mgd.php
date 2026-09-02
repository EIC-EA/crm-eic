<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_PCoCo',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'EIC_PCoco_For',
        'label_a_b' => E::ts('PCoCo - EIC Project Primary Coordinator contact for'),
        'name_b_a' => 'EIC_PCoco_Is',
        'label_b_a' => E::ts('PCoCo - EIC Project Primary Coordinator contact is'),
        'description' => E::ts('The Primary Coordinator Contact is for each project the main contact between the consortium and the EU for a particular project/contract'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
        'contact_sub_type_a' => 'EIC_Registered',
        'contact_sub_type_b' => 'EIC_Awardee',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
