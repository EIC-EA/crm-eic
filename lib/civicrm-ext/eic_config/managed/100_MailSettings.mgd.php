<?php

use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'MailAccount_CiviMail_IMAP_bouncer',
    'entity' => 'MailSettings',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'match' => ['id'],
      'values' => [
        'id' => 1,
        'domain_id' => 1,
        'name' => getenv('IMAP_BOUNCER_NAME') ?: 'CiviMail IMAP Bouncer',
        'is_default' => TRUE,
        'domain' => getenv('IMAP_MAIL_DOMAIN') ?: 'test.local',
        'localpart' => NULL,
        'return_path' => NULL,
        'protocol:name' => 'IMAP',
        'server' => getenv('IMAP_SERVER') ?: 'imap',
        'port' => NULL,
        'username' => getenv('IMAP_USERNAME') ?: 'eicmailcatcher@test.local',
        'password' => getenv('IMAP_PASSWORD') ?: 'eicmailcatcher',
        'is_ssl' => FALSE,
        'source' => NULL,
        'is_active' => TRUE,
      ],
    ],
  ],
  [
    'name' => 'MailAccount_CiviMail_IMAP_activity',
    'entity' => 'MailSettings',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'match' => ['name'],
      'values' => [
        'name' => getenv('IMAP_ACTIVITY_NAME') ?: 'CiviMail IMAP Activity',
        'server' => getenv('IMAP_SERVER') ?: 'imap',
        'username' => getenv('IMAP_USERNAME') ?: 'eicmailcatcher@test.local',
        'password' => getenv('IMAP_PASSWORD') ?: 'eicmailcatcher',
        'localpart' => NULL,
        'domain' => getenv('IMAP_MAIL_DOMAIN') ?: 'test.local',
        'protocol:name' => 'IMAP',
        'source' => NULL,
        'is_default' => FALSE,
        'is_ssl' => FALSE,
        'is_non_case_email_skipped' => FALSE,
        'is_contact_creation_disabled_if_no_match' => TRUE,
        'activity_status' => 'Completed',
        'is_active' => TRUE,
        'activity_type_id' => 12,
        'campaign_id' => NULL,
        'activity_source' => 'from',
        'activity_targets' => [
          'to',
          'cc',
          'bcc',
        ],
        'activity_assignees' => [
          'from',
        ],
      ],
    ],
  ],
];
