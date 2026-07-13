<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  'type' => 'search',
  'title' => E::ts('EIC Projects'),
  'description' => E::ts('Use on the contact to find information about the eligibility'),
  'placement' => [
    'contact_summary_tab',
  ],
  'icon' => 'fa-rocket',
];
