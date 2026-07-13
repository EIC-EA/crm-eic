<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_afsearchEICAwardeesRepresentativesSearch',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('EIC Awardees representatives'),
        'name' => 'afsearchEICAwardeesRepresentativesSearch',
        'url' => 'civicrm/EICAwardeesRepresentatives',
        'icon' => 'crm-i fa-person',
        'permission' => ['access CiviCRM'],
        'permission_operator' => 'AND',
        'parent_id.name' => 'Find Contacts',
        'weight' => 1,
      ],
      'match' => ['name', 'domain_id'],
    ],
  ],
];
