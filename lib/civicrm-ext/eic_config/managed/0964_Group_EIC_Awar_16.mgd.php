<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Group_EIC_Awar_16',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awar_16',
        'title' => E::ts('EIC Awardees'),
        'description' => E::ts('Group with all the EIC awardees contacts.
This group is use in the ACL to grant access to the contact data'),
        'saved_search_id.name' => 'All_EIC_Awardees',
        'group_type' => [],
        'is_reserved' => TRUE,
        'frontend_title' => E::ts('EIC Awardees'),
      ],
      'match' => ['name'],
    ],
  ],
];
