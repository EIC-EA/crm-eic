<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_VM',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'VM',
        'name' => 'VM',
        'url' => 'civicrm/VentureMatchServiceRequestFollowUp',
        'icon' => 'crm-i fa-money-bill-trend-up',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 114,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_VM_Navigation_New_Engagement',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'New Engagement',
        'name' => 'New Engagement',
        'url' => 'civicrm/quick-engagement',
        'icon' => 'crm-i fa-handshake-angle',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'parent_id.name' => 'VM',
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
];