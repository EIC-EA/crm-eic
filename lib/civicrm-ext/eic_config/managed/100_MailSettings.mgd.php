<?php

use CRM_EicConfig_ExtensionUtil as E;



return [
  [
    'name' => 'MailAccount_CiviMail_IMAP',
    'entity' => 'MailSettings',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'match' => ['id'],
      'values' => [
        'id' => 1,
        'name' => getenv('IMAP_NAME') ?: 'CiviMail IMAP',
        'server' => getenv('IMAP_SERVER') ?: 'imap',
        'username' => getenv('IMAP_USERNAME') ?: 'eicmailcatcher@local.test',
        'password' => getenv('IMAP_PASSWORD') ?: 'eicmailcatcher',
        'localpart' => NULL,
        'domain' => getenv('IMAP_MAIL_DOMAIN') ?: 'test.local',
        'protocol:name' => 'IMAP',
        'source' => NULL,
        'is_default' => FALSE,
        'is_ssl' => FALSE,
        'is_non_case_email_skipped' => FALSE,
        'is_contact_creation_disabled_if_no_match' => FALSE,
        'activity_status' => 'Completed',
        "is_active" => true,
        "activity_type_id" => 12,
        "campaign_id" => null,
        "activity_source" => "from",
        "activity_targets" => [
          "to",
          "cc",
          "bcc"
        ],
        "activity_assignees" => [
          "from"
        ]
      ],
    ],
  ],
];
