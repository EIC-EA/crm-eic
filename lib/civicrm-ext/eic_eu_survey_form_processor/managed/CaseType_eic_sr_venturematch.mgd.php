<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CaseType_eic_sr_venturematch',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_venturematch',
        'title' => E::ts('Service Request - EIC VentureMatch'),
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
              // The VentureMatch investment adviser is the case manager. Uses the
              // "Investment Adviser" relationship type (client-facing direction:
              // name_b_a = "Investment Adviser is"). Replaces the generic Case
              // Coordinator role on this case type. Advisers are members of the
              // EIC VentureMatch access-control group.
              'name' => 'Investment Adviser is',
              'manager' => '1',
            ],
            [
              // Founder's Primary Contact from the EIC VM Fundraising Assessment
              // EU-Survey. Uses the "Founder" relationship type (client-facing
              // direction: name_b_a = "Founder is").
              'name' => 'Founder is',
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
