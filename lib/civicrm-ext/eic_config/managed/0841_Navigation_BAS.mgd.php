<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_Community',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'Community',
        'name' => 'Community',
        'url' => 'civicrm/cp',
        'icon' => 'crm-i fa-people-group',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 113,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_CPP',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'CPP',
        'name' => 'CPP',
        'url' => 'civicrm/ecpp',
        'icon' => 'crm-i fa-building-user',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 115,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_GBE',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'GBE',
        'name' => 'GBE',
        'url' => 'civicrm/gbe',
        'icon' => 'crm-i fa-earth-europe',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 116,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_Innonext',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'Innonext',
        'name' => 'Innonext',
        'url' => 'civicrm/innonext',
        'icon' => 'crm-i fa-lightbulb',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 117,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_IPP',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'IPP',
        'name' => 'IPP',
        'url' => 'civicrm/ipp',
        'icon' => 'crm-i fa-cart-shopping',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 118,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_ITF',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'ITF',
        'name' => 'ITF',
        'url' => 'civicrm/itf',
        'icon' => 'crm-i fa-store',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 119,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_EPP',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'EPP',
        'name' => 'EPP',
        'url' => 'civicrm/EPP',
        'icon' => 'crm-i fa-handshake-simple',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 120,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_WLP',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'WLP',
        'name' => 'WLP',
        'url' => 'civicrm/wlp',
        'icon' => 'crm-i fa-venus',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 121,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],
  [
    'name' => 'Navigation_Coaching',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => 'Coaching',
        'name' => 'Coaching',
        'url' => 'civicrm/coaching',
        'icon' => 'crm-i fa-chalkboard-user',
        'permission' => [
          'access CiviCRM',
        ],
        'permission_operator' => 'AND',
        'weight' => 122,
      ],
      'match' => [
        'name',
        'domain_id',
      ],
    ],
  ],            
];