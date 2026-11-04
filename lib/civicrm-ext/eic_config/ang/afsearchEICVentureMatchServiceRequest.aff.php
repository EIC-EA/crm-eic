<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
    'type' => 'search',
    'requires' => NULL,
    'entity_type' => NULL,
    'join_entity' => NULL,
    'title' => 'EIC VentureMatch Service Request',
    'description' => NULL,
    'placement' => [
        'dashboard_dashlet',
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
    'modified_date' => '2026-10-29 13:59:39',
    'confirmation_type' => 'redirect_to_url',
    'confirmation_message' => NULL,
    'created_id' => 2,
    'locale' => NULL,
    'placement_filters' => [],
    'navigation' => NULL,
    'layout' => [
        [
        '#tag' => 'div',
        'af-fieldset' => '',
        'af-title' => 'EIC VentureMatch Service Request Follow-up',
        '#children' => [
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'div',
            'class' => 'af-container af-layout-cols',
            '#children' => [
                [
                '#text' => '
    ',
                ],
                [
                '#tag' => 'af-field',
                'name' => 'Case_CaseContact_Contact_01.display_name',
                'defn' => [
                    'label' => 'Company',
                    'input_attrs' => [],
                ],
                ],
                [
                '#text' => '
    ',
                ],
                [
                '#tag' => 'af-field',
                'name' => 'status_id:name',
                'defn' => [
                    'input_attrs' => [
                    'multiple' => TRUE,
                    ],
                    'label' => 'Status',
                    'required' => FALSE,
                    'options' => [
                    [
                        'id' => 'Requested',
                        'label' => 'Requested',
                        'name' => 'Requested',
                        'description' => '',
                        'color' => '',
                    ],
                    [
                        'id' => 'Waiting EU Survey answer',
                        'label' => 'Waiting EU Survey answer',
                        'name' => 'Waiting EU Survey answer',
                        'description' => '',
                        'color' => '',
                    ],
                    [
                        'id' => 'Calibration',
                        'label' => 'Calibration',
                        'name' => 'Calibration',
                        'description' => '',
                        'color' => '',
                    ],
                    [
                        'id' => 'Material refinement',
                        'label' => 'Material refinement',
                        'name' => 'Material refinement',
                        'description' => '',
                        'color' => '',
                    ],
                    [
                        'id' => 'Investors Outreach',
                        'label' => 'Investors Outreach',
                        'name' => 'Investors Outreach',
                        'description' => '',
                        'color' => '',
                    ],
                    [
                        'id' => 'Done',
                        'label' => 'Done',
                        'name' => 'Done',
                        'description' => '',
                        'color' => '',
                    ],
                    [
                        'id' => 'Lost',
                        'label' => 'Lost',
                        'name' => 'Lost',
                        'description' => '',
                        'color' => '',
                    ],
                    ],
                ],
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
            [
            '#tag' => 'div',
            'class' => 'af-container af-layout-cols',
            '#children' => [
                [
                '#text' => '
    ',
                ],
                [
                '#tag' => 'af-field',
                'name' => 'Case_CaseContact_Contact_01.EU_Survey_Company_Data.Sector:name',
                'defn' => [
                    'input_attrs' => [
                    'multiple' => TRUE,
                    ],
                    'label' => 'Sector',
                ],
                ],
                [
                '#text' => '
    ',
                ],
                [
                '#tag' => 'af-field',
                'name' => 'Case_CaseContact_Contact_01.EIC_Fundraising_Assessment.Current_Fundraising_Round:name',
                'defn' => [
                    'input_attrs' => [
                    'multiple' => TRUE,
                    ],
                    'label' => 'Round',
                    'help_post' => NULL,
                ],
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
            [
            '#tag' => 'crm-search-display-table',
            'search-name' => 'Contact_Summary_Cases_copy_',
            'display-name' => 'VentureMatch_Service_request_Follow_up',
            'filters' => "{case_manager_id: 'user_contact_id'}",
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
    'name' => 'afsearchEICVentureMatchServiceRequest',
    ],
];