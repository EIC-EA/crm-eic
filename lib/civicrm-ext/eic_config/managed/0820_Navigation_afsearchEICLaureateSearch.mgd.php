<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_afsearchEICLaureateSearch',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('EIC Awardees Organisation'),
        'name' => 'afsearchEICLaureateSearch',
        'url' => 'civicrm/EICAwardees',
        'icon' => 'crm-i fa-medal',
        'permission' => ['access CiviCRM'],
        'permission_operator' => 'AND',
        'parent_id.name' => 'Search',
        'weight' => 5,
      ],
      'match' => ['name', 'domain_id'],
    ],
  ],
];
