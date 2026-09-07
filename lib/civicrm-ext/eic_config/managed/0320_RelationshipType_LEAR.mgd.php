<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'RelationshipType_LEAR',
    'entity' => 'RelationshipType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name_a_b' => 'EIC_LEAR_For',
        'label_a_b' => E::ts('LEAR - EIC Organisation Legal Entity Appointed Representative fo'),
        'name_b_a' => 'EIC_LEAR_Is',
        'label_b_a' => E::ts('LEAR - EIC Organisation Legal Entity Appointed Representative is'),
        'description' => E::ts('The LEAR (Organisation Legal Entity Appointed Representative) is the formally nominated main responsible for an organisation use of the Funding and Tender Portal and thus bears the final responsibility for all the organisation actions.'),
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Organization',
        'contact_sub_type_a' => 'EIC_Registered',
        'contact_sub_type_b' => 'EIC_Awardee',
      ],
      'match' => ['name_a_b', 'name_b_a'],
    ],
  ],
];
