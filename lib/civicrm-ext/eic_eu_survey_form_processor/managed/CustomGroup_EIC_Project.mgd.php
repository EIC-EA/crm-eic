<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EIC_Project',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Project',
        'title' => E::ts('EIC Project Data'),
        'extends' => 'Case',
        'extends_entity_column_value' => ['4'],
        'weight' => 3,
        'collapse_adv_display' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Project_CustomField_EIC_Title',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Project',
        'name' => 'EIC_Title',
        'label' => E::ts('EIC Title'),
        'html_type' => 'Text',
        'default_value' => 'EIC Awardee Onboarding',
        'is_view' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Project_CustomField_Organisation_PIC_number',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Project',
        'name' => 'Organisation_PIC_number',
        'label' => E::ts('Organisation PIC number'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    // EIC Project ID (proposal number) provided by the EU-Survey. Also used to
    // match the corresponding EIC_Awardee_Project activity by Project_Number.
    'name' => 'CustomGroup_EIC_Project_CustomField_EIC_Project_ID',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Project',
        'name' => 'EIC_Project_ID',
        'label' => E::ts('EIC Project ID'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    // EIC Project Acronym provided by the EU-Survey. Used as the fallback match
    // for the EIC_Awardee_Project activity (matched on the activity subject).
    'name' => 'CustomGroup_EIC_Project_CustomField_EIC_Project_Acronym',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Project',
        'name' => 'EIC_Project_Acronym',
        'label' => E::ts('EIC Project Acronym'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    // Clickable link to the matched EIC_Awardee_Project activity. Stored as an
    // Entity Reference (FK to civicrm_activity) so the case can link through to
    // the EIC Project activity.
    'name' => 'CustomGroup_EIC_Project_CustomField_EIC_Project_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Project',
        'name' => 'EIC_Project_Activity',
        'label' => E::ts('EIC Project (activity)'),
        'data_type' => 'EntityReference',
        'html_type' => 'Autocomplete-Select',
        'fk_entity' => 'Activity',
        'is_view' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
