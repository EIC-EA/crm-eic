<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Company LinkedIn Profile URL - added to the existing "Organisation identifiers"
// custom group (EIC_Organisation_identifiers, defined in eic_config). Stored as a
// clickable Link. Received through the EIC VM Fundraising Assessment EU-Survey and
// kept as a potential future company-matching key.
//
// The group itself is owned by the eic_config extension; attaching a field to it
// from this extension follows the existing cross-extension pattern (e.g.
// nc_config adds Company_Domain_Name to the same group). update=always so the
// field re-applies on every reconcile.

return [
  [
    'name' => 'CustomGroup_EIC_Organisation_identifiers_CustomField_LinkedIn_URL',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Organisation_identifiers',
        'name' => 'LinkedIn_URL',
        'label' => E::ts('Company LinkedIn Profile URL'),
        'data_type' => 'Link',
        'html_type' => 'Link',
        'text_length' => 512,
        'help_pre' => E::ts('The company\'s LinkedIn profile URL. Received through the EIC VM Fundraising Assessment EU-Survey.'),
        'column_name' => 'linkedin_url',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
