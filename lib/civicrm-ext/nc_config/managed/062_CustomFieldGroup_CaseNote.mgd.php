<?php

use CRM_NcConfig_ExtensionUtil as E;

// "Case Note" custom group - a free-text note field available on EVERY case
// type, so agents can jot notes directly on the case.
//
// The custom group extends `Case` with NO `extends_entity_column_value`, which
// means it applies to all case types (present and future). update=always so it
// re-applies on every reconcile and stays identical across environments.

return [
  [
    'name' => 'CustomGroup_Case_Note',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Case_Note',
        'title' => E::ts('Note'),
        'extends' => 'Case',
        'weight' => 15,
        'collapse_adv_display' => FALSE,
        'table_name' => 'civicrm_value_srm_case_note',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_Case_Note_CustomField_Note',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'Case_Note',
        'name' => 'Note',
        'label' => E::ts('Note'),
        'data_type' => 'Memo',
        'html_type' => 'TextArea',
        'note_rows' => 4,
        'note_columns' => 60,
        'is_required' => FALSE,
        'is_searchable' => FALSE,
        'is_view' => FALSE,
        'is_active' => TRUE,
        'column_name' => 'note',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
