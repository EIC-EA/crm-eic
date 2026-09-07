<?php

use CRM_EicConfig_ExtensionUtil as E;

$emails = [ ["is_default" => TRUE]];
if (getenv('MAIL_INCOMING')) {
  $option = json_decode(getenv('MAIL_INCOMING'), true);
  if (array_key_exists('emails', $option))
    $emails = $option['emails'];
}

$mgd = [];
$idx = 1;
foreach($emails as $email) {
  $default_values = [
    'id' => $idx,
    'domain_id' => 1,
    'name' => "CiviMail $idx",
    'is_default' => FALSE, // There should be one default
    'domain' => 'test.local',
    'localpart' => NULL,
    'return_path' => NULL,
    'protocol:name' => 'IMAP',
    'server' => 'imap',
    'port' => NULL,
    'username' => 'eicmailcatcher@test.local',
    'password' => 'eicmailcatcher',
    'is_ssl' => FALSE,
    'source' => NULL,
    'is_active' => TRUE,
    'is_non_case_email_skipped' => FALSE,
    'is_contact_creation_disabled_if_no_match' => TRUE,
    'activity_status' => NULL,
    'is_active' => TRUE,
    'activity_type_id' => NULL,
    'campaign_id' => NULL,
    'activity_source' => NULL,
    'activity_targets' => NULL,
    'activity_assignees' => NULL
  ];  
  $mgd[] = [
    'name' => "MailAccount_CiviMail_$idx",
    'entity' => 'MailSettings',
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