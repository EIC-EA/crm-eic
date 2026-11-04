<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

/**
 * Resolve a RelationshipType id by its machine name (name_a_b).
 *
 * CaseType's `default_assignee_relationship` must be expressed as
 * "<relationshipTypeId>_a_b" or "<relationshipTypeId>_b_a" — it does not accept
 * names, and the numeric id is environment-specific (auto-increment). We look
 * it up at reconcile time so the managed definition stays portable.
 *
 * @param string $nameAB The RelationshipType.name_a_b to resolve.
 * @param string $direction Either 'a_b' or 'b_a'.
 * @return string|null e.g. "27_a_b", or NULL when the type is not found.
 */
$resolveAssigneeRelationship = static function (string $nameAB, string $direction = 'a_b'): ?string {
  $id = \Civi\Api4\RelationshipType::get(FALSE)
    ->addSelect('id')
    ->addWhere('name_a_b', '=', $nameAB)
    ->setLimit(1)
    ->execute()
    ->first()['id'] ?? NULL;

  return $id ? ($id . '_' . $direction) : NULL;
};

// Assignee = the "Investment Associate is" side of the relationship (a_b).
$investmentAssociateAssignee = $resolveAssigneeRelationship('Investment Associate is', 'a_b');

return [
  [
    'name' => 'CaseType_eic_sr_venturematch',
    'entity' => 'CaseType',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_venturematch',
        'title' => 'Service Request - EIC VentureMatch',
        'definition' => [
          'restrictActivityAsgmtToCmsUser' => 0,
          'activityAsgmtGrps' => [
            'EIC_VentureMatch',
          ],
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
            [
              'name' => 'Meeting',
            ],
          ],
          'statuses' => [
            'Requested',
            'Waiting EU Survey answer',
            'Calibration',
            'Material refinement',
            'Investors Outreach',
            'Done',
            'Lost',
          ],
          'activitySets' => [
            [
              'name' => 'standard_timeline',
              'label' => 'Standard Timeline',
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Meeting',
                  'label' => 'Meeting',
                  'status' => 'To plan',
                  'reference_activity' => 'Open Case',
                  'reference_offset' => '1',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '2',
                  'default_subject' => 'Calibration session',
                  'default_assignee_relationship' => $investmentAssociateAssignee,
                  'default_assignee_contact' => [],
                ],
              ],
            ],
            [
              'name' => 'timeline_1',
              'label' => 'Next Checkin',
              'timeline' => 1,
              'activityTypes' => [
                [
                  'name' => 'Follow up',
                  'label' => 'Follow up',
                  'status' => 'Scheduled',
                  'reference_activity' => 'Follow up',
                  'reference_offset' => '35',
                  'reference_select' => 'newest',
                  'default_assignee_type' => '2',
                  'default_subject' => 'Checkin',
                  'default_assignee_relationship' => $investmentAssociateAssignee,
                  'default_assignee_contact' => [],
                ],
              ],
            ],
          ],
          'timelineActivityTypes' => [
            [
              'name' => 'Meeting',
              'label' => 'Meeting',
              'status' => 'To plan',
              'reference_activity' => 'Open Case',
              'reference_offset' => '1',
              'reference_select' => 'newest',
              'default_assignee_type' => '2',
              'default_subject' => 'Calibration session',
              'default_assignee_relationship' => $investmentAssociateAssignee,
              'default_assignee_contact' => [],
            ],
            [
              'name' => 'Follow up',
              'label' => 'Follow up',
              'status' => 'Scheduled',
              'reference_activity' => 'Follow up',
              'reference_offset' => '35',
              'reference_select' => 'newest',
              'default_assignee_type' => '2',
              'default_subject' => 'Checkin',
              'default_assignee_relationship' => $investmentAssociateAssignee,
              'default_assignee_contact' => [],
            ],
          ],
          'caseRoles' => [
            [
              'name' => 'Investment Associate',
              'groups' => [
                'EIC_VentureMatch',
              ],
              'manager' => '1',
            ],
            [
              'name' => 'Founder of',
            ],
          ],
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];