<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
    'type' => 'search',
    'requires' => NULL,
    'entity_type' => NULL,
    'join_entity' => NULL,
    'title' => 'EIC Engagement',
    'description' => 'EIC VentureMatch Engagement',
    'placement' => [
        'contact_summary_block',
        'contact_summary_tab',
    ],
    'placement_filters' => [
        'contact_type' => [
        'EIC_Awardee',
        'EIC_Organisation',
        ],
    ],
    'placement_weight' => NULL,
    'tags' => NULL,
    'icon' => 'fa-handshake-angle',
    'server_route' => '',
    'is_public' => FALSE,
    'permission' => [
        'access CiviCRM',
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
    'modified_date' => '2026-10-30 14:56:25',
    'confirmation_type' => 'redirect_to_url',
    'confirmation_message' => NULL,
    'created_id' => 2,
    'locale' => NULL,
    'layout' => [
        [
        '#tag' => 'div',
        'af-fieldset' => '',
        'af-title' => 'EIC VentureMatch Engagement',
        '#children' => [
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'crm-search-display-table',
            'search-name' => 'Custom_EIC_Contact_EIC_Awardee_VentureMatch_Engagement',
            'display-name' => 'VentureMatch_Service_request_summary_copy_',
            'filters' => "{'Client_Roles.EIC_Awardee': options.contact_id}",
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
    'name' => 'afsearchEICVentureMatchEngagement',
    ],
];