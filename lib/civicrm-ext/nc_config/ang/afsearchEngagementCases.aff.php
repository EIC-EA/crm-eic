<?php
use CRM_NcConfig_ExtensionUtil as E;

return [
  'type' => 'search',
  'title' => E::ts('Engagement Cases'),
  'description' => E::ts('Engagement Cases'),
  'placement' => [
    'dashboard_dashlet',
  ],
  'icon' => 'fa-list-alt',
  'server_route' => 'civicrm/engagement-cases',
];
