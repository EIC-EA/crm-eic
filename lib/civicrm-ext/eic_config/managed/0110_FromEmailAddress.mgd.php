<?php

use CRM_EicConfig_ExtensionUtil as E;

$emails = [ ["email" => "eicmailcatcher@test.local"]];
if (getenv('MAIL_OUTGOING')) {
  $option = json_decode(getenv('MAIL_OUTGOING'), true);
  if (array_key_exists('emails', $option))
    $emails = $option['emails'];
}

$mgd = [];
$idx = 1;
foreach($emails as $email) {
  $default_values = [
    'id' => $idx,
    'display_name' => "$idx name",
    'email' => '',
    'description' => '',
    'is_active' => TRUE,
    'is_default' => FALSE,
    'domain_id' => 1
  ];  
  $mgd[] = [
    'name' => 'SiteEmailAddress_'.$idx,
    'entity' => 'SiteEmailAddress',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'match' => ['id'],
      'values' => array_merge($default_values, $email),
    ],
  ];
  $idx++;
}

return $mgd;