<?php

use CRM_NcConfig_ExtensionUtil as E;

return [
    [
        'name' => 'CaseType_eic_investor_onboarding',
        'entity' => 'CaseType',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'eic_investor_onboarding',
                'title' => E::ts('Investor Onboarding'),
                'description' => E::ts('Investor Onboarding'),
                'definition' => [
                    'restrictActivityAsgmtToCmsUser' => 1,
                    'activityAsgmtGrps' => [],
                    'activityTypes' => [
                        [
                            'name' => 'Open Case',
                            'max_instances' => '1',
                        ],
                        [
                            'name' => 'Email',
                        ],
                        [
                            'name' => 'Follow up',
                        ],
                        [
                            'name' => 'Meeting',
                        ],
                        [
                            'name' => 'Phone Call',
                        ],
                    ],
                    'activitySets' => [
                        [
                            'name' => 'standard_timeline',
                            'label' => E::ts('Outreach'),
                            'timeline' => 1,
                            'activityTypes' => [
                                [
                                    'name' => 'Outreach response',
                                    'label' => E::ts('Outreach response'),
                                    'status' => 'Scheduled',
                                    'reference_activity' => [],
                                    'reference_offset' => '14',
                                    'reference_select' => 'newest',
                                    'default_assignee_type' => '1',
                                ],
                            ],
                        ],
                        [
                            'name' => 'timeline_1',
                            'label' => E::ts('Onboarding'),
                            'timeline' => 1,
                            'activityTypes' => [
                                [
                                    'name' => 'Onboarding call',
                                    'label' => E::ts('Onboarding call'),
                                    'status' => 'Scheduled',
                                    'reference_activity' => 'Outreach response',
                                    'reference_offset' => '1',
                                    'reference_select' => 'newest',
                                    'default_assignee_type' => '1',
                                ],
                                [
                                    'name' => 'Onboarding Survey B',
                                    'label' => E::ts('Onboarding Survey B'),
                                    'status' => 'Scheduled',
                                    'reference_activity' => 'Onboarding call',
                                    'reference_offset' => '1',
                                    'reference_select' => 'newest',
                                    'default_assignee_type' => '1',
                                ],
                            ],
                        ],
                    ],
                    'timelineActivityTypes' => [
                        [
                            'name' => 'Outreach response',
                            'label' => E::ts('Outreach response'),
                            'status' => 'Scheduled',
                            'reference_activity' => [],
                            'reference_offset' => '14',
                            'reference_select' => 'newest',
                            'default_assignee_type' => '1',
                        ],
                        [
                            'name' => 'Onboarding call',
                            'label' => E::ts('Onboarding call'),
                            'status' => 'Scheduled',
                            'reference_activity' => 'Outreach response',
                            'reference_offset' => '1',
                            'reference_select' => 'newest',
                            'default_assignee_type' => '1',
                        ],
                        [
                            'name' => 'Onboarding Survey B',
                            'label' => E::ts('Onboarding Survey B'),
                            'status' => 'Scheduled',
                            'reference_activity' => 'Onboarding call',
                            'reference_offset' => '1',
                            'reference_select' => 'newest',
                            'default_assignee_type' => '1',
                        ],
                    ],
                    'caseRoles' => [
                        [
                            'name' => 'Case Coordinator',
                            'creator' => '1',
                            'manager' => '1',
                        ],
                    ],
                ],
            ],
            'match' => ['name'],
        ],
    ],
    [
        'name' => 'CaseType_eic_engagement',
        'entity' => 'CaseType',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'eic_engagement',
                'title' => E::ts('Engagement'),
                'description' => E::ts('Engagement'),
                'definition' => [
                    'restrictActivityAsgmtToCmsUser' => 0,
                    'activityAsgmtGrps' => [],
                    'activityTypes' => [
                        [
                            'name' => 'Open Case',
                            'max_instances' => '1',
                        ],
                        [
                            'name' => 'Email',
                        ],
                        [
                            'name' => 'Follow up',
                        ],
                        [
                            'name' => 'Meeting',
                        ],
                        [
                            'name' => 'Phone Call',
                        ],
                    ],
                    'activitySets' => [
                        [
                            'name' => 'standard_timeline',
                            'label' => E::ts('Engagement'),
                            'timeline' => 1,
                            'activityTypes' => [
                                [
                                    'name' => 'Introduction response',
                                    'label' => E::ts('Introduction response'),
                                    'status' => 'Scheduled',
                                    'reference_activity' => [],
                                    'reference_offset' => '21',
                                    'reference_select' => 'newest',
                                    'default_assignee_type' => '1',
                                ],
                            ],
                        ],
                    ],
                    'timelineActivityTypes' => [
                        [
                            'name' => 'Introduction response',
                            'label' => E::ts('Introduction response'),
                            'status' => 'Scheduled',
                            'reference_activity' => [],
                            'reference_offset' => '21',
                            'reference_select' => 'newest',
                            'default_assignee_type' => '1',
                        ],
                    ],
                    'caseRoles' => [
                        [
                            'name' => 'Case Coordinator',
                            'creator' => '1',
                            'manager' => '1',
                        ],
                    ],
                ],
            ],
            'match' => ['name'],
        ],
    ],
];
