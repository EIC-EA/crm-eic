<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
    'type' => 'search',
    'requires' => NULL,
    'entity_type' => NULL,
    'join_entity' => NULL,
    'title' => 'Activities',
    'description' => NULL,
    'placement' => [
        'contact_summary_block',
    ],
    'placement_filters' => [
        'contact_type' => [],
    ],
    'placement_weight' => NULL,
    'tags' => NULL,
    'icon' => 'fa-list-alt',
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
    'modified_date' => '2026-10-28 13:51:31',
    'confirmation_type' => 'redirect_to_url',
    'confirmation_message' => NULL,
    'created_id' => 2,
    'locale' => NULL,
    'layout' => [
        [
        '#tag' => 'div',
        'af-fieldset' => '',
        'af-title' => 'Activities',
        '#children' => [
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'crm-search-display-table',
            'search-name' => 'Custom_EIC_Contact_Block_VentureMatch_Service_request_Case_Active_Activities',
            'display-name' => 'VentureMatch_Service_request_Activities_summary_copy_',
            'filters' => '{target_contact_id: options.contact_id}',
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
    'name' => 'afsearchActivities',
    ],
];