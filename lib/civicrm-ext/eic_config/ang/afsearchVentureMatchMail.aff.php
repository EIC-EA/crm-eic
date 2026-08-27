<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  'type' => 'search',
  'title' => E::ts('VentureMatch Email'),
  'placement' => [
    'contact_summary_tab',
  ],
  'placement_filters' => [
    'contact_type' => ['Organization'],
  ],
  'icon' => 'fa-envelope',
];
