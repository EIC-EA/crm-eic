<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
    'type' => 'search',
    'requires' => NULL,
    'entity_type' => NULL,
    'join_entity' => NULL,
    'title' => 'VentureMatch Service request summary',
    'description' => NULL,
    'placement' => [
        'contact_summary_block',
    ],
    'placement_filters' => [],
    'placement_weight' => 50,
    'tags' => NULL,
    'icon' => 'fa-list-alt',
    'server_route' => '',
    'is_public' => FALSE,
    'permission' => [
        'access CiviCRM',
        'access my cases and activities',
    ],
    'permission_operator' => 'AND',
    'redirect' => NULL,
    'submit_enabled' => TRUE,
    'submit_limit' => NULL,
    'submit_limit_per_user' => NULL,
    'create_submission' => FALSE,
    'manual_processing' => FALSE,
    'allow_verification_by_email' => FALSE,
    'email_confirmation_template_id' => NULL,
    'autosave_draft' => FALSE,
    'navigation' => NULL,
    'modified_date' => '2026-10-27 13:58:32',
    'confirmation_type' => 'redirect_to_url',
    'confirmation_message' => NULL,
    'created_id' => 2,
    'locale' => NULL,
    'layout' => [
        [
        '#tag' => 'div',
        'af-fieldset' => '',
        'af-title' => 'VentureMatch Service request summary',
        '#children' => [
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'crm-search-display-table',
            'search-name' => 'Contact_Summary_Cases_copy_',
            'display-name' => 'Contact_Summary_Cases_Table_copy_',
            'filters' => "{'Case_CaseContact_Contact_01.id': options.contact_id}",
            ],
            [
            '#text' => '
',
            ],
        ],
        ],
        [
        '#text' => '
',
        ],
    ],
    'name' => 'afsearchVentureMatchServiceRequestSummary',
    ],
];