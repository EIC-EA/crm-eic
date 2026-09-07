<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CaseType_eu_survey_import',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eu_survey_import',
        'title' => E::ts('EU Survey Import'),
        'definition' => [
          'restrictActivityAsgmtToCmsUser' => 0,
          'activityTypes' => [
            [
              'name' => 'Open Case',
              'max_instances' => '1',
            ],
            [
              'name' => 'eu_survey',
              'max_instances' => '1',
            ],
            [
              'name' => 'Email',
            ],
            [
              'name' => 'Follow up',
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
          'caseRoles' => [
            [
              'name' => 'Case Coordinator',
              'creator' => '1',
              'manager' => '1',
            ],
            [
              'name' => 'Employee of',
            ],
            [
              'name' => 'Employer of',
            ],
          ],
        ],
      ],
      'match' => ['name'],
    ],
  ],
];
