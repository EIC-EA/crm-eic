<?php
use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_Engagement_Cases',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('Engagement Cases'),
        'name' => 'Engagement Cases',
        'url' => 'civicrm/engagement-cases',
        'icon' => 'crm-i fa-briefcase',
        'permission' => ['access CiviCRM'],
        'permission_operator' => 'AND',
        'parent_id.name' => 'Search',
        'weight' => 7,
      ],
      'match' => ['name', 'domain_id'],
    ],
  ],
];
