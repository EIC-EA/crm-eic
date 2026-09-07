<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_Find_Contacts',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('Find Contacts'),
        'name' => 'Find Contacts',
        'url' => 'civicrm/contact/search?reset=1',
        'permission_operator' => '',
        'parent_id.name' => 'Search',
        'has_separator' => NULL,
        'weight' => 4,
      ],
      'match' => ['name', 'domain_id'],
    ],
  ],
];
