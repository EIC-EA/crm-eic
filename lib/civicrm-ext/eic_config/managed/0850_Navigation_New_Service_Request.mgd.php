<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_New_Service_Request',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('New Service Request'),
        'name' => 'New Service Request',
        'url' => 'civicrm/QuickVentureMatchSR',
        'icon' => 'crm-i fa-plus',
        'permission' => ['access CiviCRM'],
        'permission_operator' => 'AND',
        'parent_id.name' => 'VM',
        'weight' => 1,
      ],
      'match' => ['name', 'domain_id'],
    ],
  ],
];
