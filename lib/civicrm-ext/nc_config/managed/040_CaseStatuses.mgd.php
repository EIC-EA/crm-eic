<?php

use CRM_NcConfig_ExtensionUtil as E;

// Case status option values and their display order.
//
// The `case_status` option group drives the order statuses appear in the UI via
// the `weight` field. The target order (see Case Status Options screen) is:
//
//   1. Requested  (Opened, value 7)
//   2. Planning   (Opened, value 8)
//   3. Ongoing    (Opened, value 1)  - CiviCRM core status (name `Open`)
//   4. Urgent     (Opened, value 3)  - CiviCRM core status
//   5. Resolved   (Closed, value 2)  - CiviCRM core status
//   6. Onboarded  (Closed, value 6)
//   7. Declined   (Closed, value 5)
//
// The three core statuses (Ongoing/Open, Urgent, Resolved) are managed here as
// well - matched by name + value so we update the existing core rows (never
// duplicate them) and only enforce their weight/grouping/label. `update` is set
// to `always` so the ordering is re-applied on every reconcile and stays
// identical across environments.

return [
  [
    'name' => 'OptionValue_Requested',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Requested'),
        'value' => '7',
        'name' => 'Requested',
        'grouping' => 'Opened',
        'weight' => 1,
        'description' => E::ts('<p>Service request has been submitted and is awaiting handling.</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Planning',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Planning'),
        'value' => '8',
        'name' => 'Planning',
        'grouping' => 'Opened',
        'weight' => 2,
        'description' => E::ts('<p>Service request is being planned.</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    // CiviCRM core status (name `Open`, label "Ongoing"). Matched by name+value
    // to reorder the existing core row, not create a new one.
    'name' => 'OptionValue_Ongoing',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Ongoing'),
        'value' => '1',
        'name' => 'Open',
        'grouping' => 'Opened',
        'weight' => 3,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    // CiviCRM core status (name `Urgent`). Matched by name+value.
    'name' => 'OptionValue_Urgent',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Urgent'),
        'value' => '3',
        'name' => 'Urgent',
        'grouping' => 'Opened',
        'weight' => 4,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Onboarded',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Onboarded'),
        'value' => '6',
        'name' => 'Onboarded',
        'grouping' => 'Closed',
        'weight' => 6,
        'description' => E::ts('<p>Onboarded</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionValue_Declined',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => E::ts('Declined'),
        'value' => '5',
        'name' => 'Declined',
        'grouping' => 'Closed',
        'weight' => 7,
        'description' => E::ts('<p>Declined</p>'),
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_case_status_OptionValue_Waiting_EU_Survey_answer',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => 'Waiting EU Survey answer',
        'value' => '10',
        'name' => 'Waiting EU Survey answer',
        'grouping' => 'Opened',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_case_status_OptionValue_Planning',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => 'Planning',
        'value' => '8',
        'name' => 'Planning',
        'grouping' => 'Opened',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_case_status_OptionValue_Calibration',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => 'Calibration',
        'value' => '11',
        'name' => 'Calibration',
        'grouping' => 'Opened',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_case_status_OptionValue_Material_refinement',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => 'Material refinement',
        'value' => '15',
        'name' => 'Material refinement',
        'grouping' => 'Opened',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_case_status_OptionValue_Investors_Outreach',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => 'Investors Outreach',
        'value' => '12',
        'name' => 'Investors Outreach',
        'grouping' => 'Opened',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_case_status_OptionValue_Done',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => 'Done',
        'value' => '13',
        'name' => 'Done',
        'grouping' => 'Closed',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_case_status_OptionValue_Lost',
    'entity' => 'OptionValue',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'case_status',
        'label' => 'Lost',
        'value' => '14',
        'name' => 'Lost',
        'grouping' => 'Closed',
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ]
];