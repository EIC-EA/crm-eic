<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CaseType_eic_awardee_onboarding',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_awardee_onboarding',
        'title' => E::ts('EIC Awardee Onboarding'),
        'definition' => [
          'restrictActivityAsgmtToCmsUser' => 0,
          'activityTypes' => [
            [
              'name' => 'Open Case',
              'max_instances' => '1',
            ],
            [
              'name' => 'eic_accelerator_onboarding_survey',
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
              'name' => 'EIC_KAM_Is',
              'manager' => '1',
            ],
          ],
        ],
      ],
      'match' => ['name'],
    ],
  ],
];
