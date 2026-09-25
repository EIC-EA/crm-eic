<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CaseType_eic_sr_global_business_expansion',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_global_business_expansion',
        'title' => E::ts('Service Request - EIC Global Business Expansion'),
        'definition' => [
          'restrictActivityAsgmtToCmsUser' => 0,
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
              'name' => 'Task',
            ],
          ],
          'activitySets' => [
            [
              'name' => 'standard_timeline',
              'label' => E::ts('Standard Timeline'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Open Case',
                  'status' => 'Completed',
                  'label' => E::ts('Open Case'),
                ],
              ],
            ],
          ],
          'timelineActivityTypes' => [
            [
              'name' => 'Open Case',
              'status' => 'Completed',
              'label' => E::ts('Open Case'),
            ],
          ],
          'caseRoles' => [
            [
              'name' => 'Case Coordinator',
              'manager' => '1',
            ],
          ],
          'statuses' => [
            'Requested',
            'Planning',
            'Open',
            'Resolved',
            'Closed',
            'Declined',
          ],
        ],
      ],
      'match' => ['name'],
    ],
  ],
];
