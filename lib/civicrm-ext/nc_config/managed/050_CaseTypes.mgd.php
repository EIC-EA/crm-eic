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
            [
              'name' => 'Task',
            ],
          ],
          'activitySets' => [
            [
              'name' => 'standard_timeline',
              'label' => E::ts('Outreach'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Follow up',
                  'label' => E::ts('Follow up'),
                  'status' => 'Scheduled',
                  'reference_activity' => [],
                  'reference_offset' => '14',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Outreach Response',
                ],
              ],
            ],
            [
              'name' => 'timeline_1',
              'label' => E::ts('Onboarding'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Phone Call',
                  'label' => E::ts('Phone Call'),
                  'status' => 'Scheduled',
                  'reference_activity' => 'Follow up',
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Onboarding Call',
                ],
                [
                  'name' => 'Task',
                  'label' => E::ts('Task'),
                  'status' => 'Scheduled',
                  'reference_activity' => 'Phone Call',
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Onboarding Survey B',
                ],
              ],
            ],
          ],
          'timelineActivityTypes' => [
            [
              'name' => 'Follow up',
              'label' => E::ts('Follow up'),
              'status' => 'Scheduled',
              'reference_activity' => [],
              'reference_offset' => '14',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Outreach Response',
            ],
            [
              'name' => 'Phone Call',
              'label' => E::ts('Phone Call'),
              'status' => 'Scheduled',
              'reference_activity' => 'Follow up',
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Onboarding Call',
            ],
            [
              'name' => 'Task',
              'label' => E::ts('Task'),
              'status' => 'Scheduled',
              'reference_activity' => 'Phone Call',
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Onboarding Survey B',
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
            [
              'name' => 'Task',
            ],
          ],
          'activitySets' => [
            [
              'name' => 'standard_timeline',
              'label' => E::ts('Engagement'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Follow up',
                  'label' => E::ts('Follow up'),
                  'status' => 'Scheduled',
                  'reference_activity' => 'Open Case',
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Expression of Interest Response',
                ],
                [
                  'name' => 'Follow up',
                  'label' => E::ts('Follow up'),
                  'status' => 'Scheduled',
                  'reference_activity' => 'Follow up',
                  'reference_offset' => '21',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Introduction Response',
                ],
              ],
            ],
          ],
          'timelineActivityTypes' => [
            [
              'name' => 'Follow up',
              'label' => E::ts('Follow up'),
              'status' => 'Scheduled',
              'reference_activity' => 'Open Case',
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Expression of Interest Response',
            ],
            [
              'name' => 'Follow up',
              'label' => E::ts('Follow up'),
              'status' => 'Scheduled',
              'reference_activity' => 'Follow up',
              'reference_offset' => '21',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Introduction Response',
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
    'name' => 'CaseType_eic_vm_beneficiary_onboarding',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_beneficiary_onboarding',
        'title' => E::ts('VentureMatch Beneficiary Onboarding'),
        'description' => E::ts('VentureMatch Beneficiary Onboarding'),
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
            [
              'name' => 'Task',
            ],
          ],
          'activitySets' => [
            [
              'name' => 'standard_timeline',
              'label' => E::ts('Outreach'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Follow up',
                  'label' => E::ts('Follow up'),
                  'status' => 'Scheduled',
                  'reference_activity' => 'Open Case',
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Outreach Response',
                ],
              ],
            ],
            [
              'name' => 'timeline_1',
              'label' => E::ts('Onboarding'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Task',
                  'label' => E::ts('Task'),
                  'status' => 'Scheduled',
                  'reference_activity' => 'Follow up',
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Beneficiary Onboarding Survey',
                ],
                [
                  'name' => 'Phone Call',
                  'label' => E::ts('Phone Call'),
                  'status' => 'Scheduled',
                  'reference_activity' => 'Task',
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Onboarding Call',
                ],
              ],
            ],
          ],
          'timelineActivityTypes' => [
            [
              'name' => 'Follow up',
              'label' => E::ts('Follow up'),
              'status' => 'Scheduled',
              'reference_activity' => 'Open Case',
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Outreach Response',
            ],
            [
              'name' => 'Task',
              'label' => E::ts('Task'),
              'status' => 'Scheduled',
              'reference_activity' => 'Follow up',
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Beneficiary Onboarding Survey',
            ],
            [
              'name' => 'Phone Call',
              'label' => E::ts('Phone Call'),
              'status' => 'Scheduled',
              'reference_activity' => 'Task',
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Onboarding Call',
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
    'name' => 'CaseType_eic_vm_beneficiary_support',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_vm_beneficiary_support',
        'title' => E::ts('VentureMatch Beneficiary Support'),
        'description' => E::ts('VentureMatch Beneficiary Support'),
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
            [
              'name' => 'Task',
            ],
          ],
          'activitySets' => [
            [
              'name' => 'standard_timeline',
              'label' => E::ts('Support'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Task',
                  'label' => E::ts('Task'),
                  'status' => 'Scheduled',
                  'reference_activity' => [],
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '1',
                  'default_subject' => 'Beneficiary Support Task',
                ],
              ],
            ],
          ],
          'timelineActivityTypes' => [
            [
              'name' => 'Task',
              'label' => E::ts('Task'),
              'status' => 'Scheduled',
              'reference_activity' => [],
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '1',
              'default_subject' => 'Beneficiary Support Task',
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
