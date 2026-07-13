<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  'type' => 'block',
  'entity_type' => 'Contact',
  'join_entity' => 'Website',
  'title' => E::ts('Contact Website(s)'),
  'icon' => 'fa-list-alt',
];
