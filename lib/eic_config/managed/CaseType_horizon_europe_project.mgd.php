<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CaseType_horizon_europe_project',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'horizon_europe_project',
        'title' => E::ts('Horizon Europe Project'),
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
              'label' => E::ts('Standard Timeline'),
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Open Case',
                  'status' => 'Completed',
                  'label' => E::ts('Open Case'),
                  'default_assignee_type' => '1',
                ],
              ],
            ],
          ],
          'timelineActivityTypes' => [
            [
              'name' => 'Open Case',
              'status' => 'Completed',
              'label' => E::ts('Open Case'),
              'default_assignee_type' => '1',
            ],
          ],
          'caseRoles' => [],
        ],
      ],
      'match' => ['name'],
    ],
  ],
];
