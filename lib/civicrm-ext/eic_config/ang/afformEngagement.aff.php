<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
    [
    'type' => 'form',
    'requires' => NULL,
    'entity_type' => NULL,
    'join_entity' => NULL,
    'title' => 'New Engagement',
    'description' => NULL,
    'placement' => [
        'contact_summary_actions',
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
    'server_route' => 'civicrm/quick-engagement',
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
    'modified_date' => '2026-10-30 16:06:00',
    'confirmation_type' => 'show_confirmation_message',
    'confirmation_message' => '<p>Engagement created. The case ID is <strong>[Case1.0.id]</strong>. Please add this ID to the email exchanges to automatically link them to this engagement flow.</p>
',
    'created_id' => 2,
    'locale' => NULL,
    'layout' => [
        [
        '#tag' => 'af-form',
        'ctrl' => 'afform',
        '#children' => [
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'af-entity',
            'data' => [
                'case_type_id' => 4,
                'contact_id' => [
                'Organization1',
                'Organization2',
                ],
                'Client_Roles.Investor' => 'Organization2',
                'Client_Roles.EIC_Awardee' => 'Organization1',
            ],
            'actions' => [
                'create' => TRUE,
                'update' => FALSE,
            ],
            'type' => 'Case',
            'name' => 'Case1',
            'label' => 'Case 1',
            'security' => 'RBAC',
            ],
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'af-entity',
            'data' => [],
            'type' => 'Organization',
            'name' => 'Organization1',
            'label' => 'Organization 1',
            'actions' => [
                'create' => FALSE,
                'update' => TRUE,
            ],
            'security' => 'RBAC',
            'autofill' => 'entity_id',
            ],
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'af-entity',
            'data' => [],
            'type' => 'Organization',
            'name' => 'Organization2',
            'label' => 'Organization 2',
            'actions' => [
                'create' => TRUE,
                'update' => TRUE,
            ],
            'security' => 'RBAC',
            'url-autofill' => '1',
            ],
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'fieldset',
            'af-fieldset' => 'Case1',
            'class' => 'af-container af-layout-cols',
            'af-title' => 'New Engagement',
            '#children' => [
                [
                '#text' => '
    ',
                ],
                [
                '#tag' => 'af-field',
                'name' => 'subject',
                'defn' => [
                    'afform_default' => 'Engagement',
                    'input_attrs' => [],
                ],
                ],
                [
                '#text' => '
    ',
                ],
                [
                '#tag' => 'af-field',
                'name' => 'Case_Note.Note',
                'defn' => [
                    'label' => 'Note',
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
                '#tag' => 'fieldset',
                'af-fieldset' => 'Organization2',
                'class' => 'af-container',
                'af-title' => 'Investor',
                '#children' => [
                    [
                    '#text' => '
        ',
                    ],
                    [
                    '#tag' => 'af-field',
                    'name' => 'id',
                    'defn' => [
                        'required' => TRUE,
                        'input_attrs' => [
                        'quickAdd' => [
                            'civicrm/quick-add/Organization',
                        ],
                        ],
                        'label' => 'Please Select the Investor company',
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
                '#tag' => 'fieldset',
                'af-fieldset' => 'Organization1',
                'class' => 'af-container',
                'af-title' => 'EIC Awardee',
                '#children' => [
                    [
                    '#text' => '
        ',
                    ],
                    [
                    '#tag' => 'af-field',
                    'name' => 'id',
                    'defn' => [
                        'required' => TRUE,
                        'input_attrs' => [],
                        'label' => 'Please select the EIC Awardee company',
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
            ],
            ],
            [
            '#text' => '
    ',
            ],
            [
            '#tag' => 'button',
            'class' => 'af-button btn btn-primary',
            'crm-icon' => 'fa-check',
            'ng-click' => 'afform.submit()',
            'ng-if' => 'afform.showSubmitButton',
            '#children' => [
                [
                '#text' => 'Submit',
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
    ],
    'name' => 'afformEngagement',
    ],
];