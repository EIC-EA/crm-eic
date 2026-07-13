<?php
use CRM_NcConfig_ExtensionUtil as E;

return [
  'type' => 'search',
  'title' => E::ts('Investor Representatives & Investors'),
  'description' => E::ts('Investor Representatives & Investors'),
  'placement' => [
    'dashboard_dashlet',
  ],
  'icon' => 'fa-list-alt',
  'server_route' => 'civicrm/view/investor-representatives',
];
