<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  'type' => 'search',
  'title' => E::ts('EIC Awardees by Org'),
  'icon' => 'fa-rocket',
  'server_route' => 'civicrm/EICAwardeesByOrg',
];
