<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// NOTE: EIC Community is a "bonus" Service Request that was not requested.
// The case type is shipped so it exists in the system, but no Form Processor
// action creates this case automatically. Its trigger question and context
// custom fields are not yet defined (to be specified later).
return [
  [
    'name' => 'CaseType_eic_sr_community',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_community',
        'title' => E::ts('Service Request - EIC Community'),
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
