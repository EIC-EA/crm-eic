<?php

use CRM_EicConfig_ExtensionUtil as E;

$fromName = getenv('SITE_FROM_DISPLAY_NAME') ?: 'EIC Info';
$fromEmail = getenv('SITE_FROM_MAIL') ?: 'info@test.local';

return [
  [
    'name' => 'SiteEmailAddress_Default',
    'entity' => 'SiteEmailAddress',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'match' => ['id'],
      'values' => [
        'id' => 1,
        'display_name' => $fromName,
        'email' => $fromEmail,
        'description' => 'Default domain email address and from name.',
        'is_active' => TRUE,
        'is_default' => TRUE,
      ],
    ],
  ],
];
