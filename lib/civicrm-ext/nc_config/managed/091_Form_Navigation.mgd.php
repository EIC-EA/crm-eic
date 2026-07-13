<?php
use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'Navigation_afsearchInvestorRepresentativesInvestors',
    'entity' => 'Navigation',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'label' => E::ts('Investor Representatives & Investors'),
        'name' => 'afsearchInvestorRepresentativesInvestors',
        'url' => 'civicrm/view/investor-representatives',
        'icon' => 'crm-i fa-list-alt',
        'permission' => ['access CiviCRM'],
        'permission_operator' => 'AND',
        'parent_id.name' => 'Find Contacts',
        'weight' => 3,
      ],
      'match' => ['name', 'domain_id'],
    ],
  ],
];
