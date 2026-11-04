<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
    'type' => 'search',
    'title' => 'Engagement',
    'description' => 'EIC VentureMatch Engagement display in Investor contact',
    'placement' => [
        'contact_summary_tab',
    ],
    'placement_filters' => [
        'contact_type' => [
        'Investor',
        ],
    ],
    'icon' => 'fa-handshake-angle',
    'server_route' => '',
    'permission' => [
        'access CiviCRM',
    ],
    'permission_operator' => 'AND',
    'confirmation_type' => 'redirect_to_url',
    'created_id' => 2,
    'modified_date' => '2026-10-30 14:51:32',
    'requires' => NULL,
    'entity_type' => NULL,
    'join_entity' => NULL,
    'placement_weight' => NULL,
    'tags' => NULL,
    'is_public' => FALSE,
    'redirect' => NULL,
    'submit_enabled' => TRUE,
    'submit_limit' => NULL,
    'submit_limit_per_user' => NULL,
    'create_submission' => NULL,
    'manual_processing' => NULL,
    'allow_verification_by_email' => NULL,
    'email_confirmation_template_id' => NULL,
    'autosave_draft' => NULL,
    'navigation' => NULL,
    'confirmation_message' => NULL,
    'locale' => NULL,
    'layout' => [
        [
        '#tag' => 'div',
        'af-fieldset' => '',
        '#children' => [
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'crm-search-display-table',
            'search-name' => 'Custom_EIC_Contact_EIC_Awardee_VentureMatch_Engagement',
            'display-name' => 'Investor_Engagement',
            'filters' => "{'Client_Roles.Investor': options.contact_id}",
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
    'name' => 'afsearchEngagement',
    ],
];