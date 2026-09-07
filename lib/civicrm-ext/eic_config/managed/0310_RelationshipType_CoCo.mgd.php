<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_Coco',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'EIC_Coco_For',
        'label_a_b' => E::ts('CoCo - EIC Project Coordinator Contact for'),
        'name_b_a' => 'EIC_Coco_Is',
        'label_b_a' => E::ts('CoCo - EIC Project Coordinator Contact is'),
        'description' => E::ts('Coordinator Contact (CoCo) are added by the PCoCo for the project/contract (unlimited number possible)'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
        'contact_sub_type_a' => 'EIC_Registered',
        'contact_sub_type_b' => 'EIC_Awardee',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
