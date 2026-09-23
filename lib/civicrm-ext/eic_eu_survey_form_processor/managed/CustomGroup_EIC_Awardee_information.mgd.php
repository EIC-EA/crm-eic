<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EIC_Awardee_information',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardee_information',
        'title' => E::ts('EIC Awardee information'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_awardee_onboarding'],
        'weight' => 3,
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_eic_awardee_information',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_EIC_Title',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'EIC_Title',
        'label' => E::ts('EIC Title'),
        'html_type' => 'Text',
        'default_value' => 'EIC Awardee Onboarding',
        'is_view' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'eic_title',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_Organisation_PIC_number',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'Organisation_PIC_number',
        'label' => E::ts('Organisation PIC number'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'organisation_pic_number',
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
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_EIC_Project_ID',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'EIC_Project_ID',
        'label' => E::ts('EIC Project ID'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'eic_project_id',
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
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_EIC_Project_Acronym',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'EIC_Project_Acronym',
        'label' => E::ts('EIC Project Acronym'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'eic_project_acronym',
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
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_EIC_Project_Activity',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'EIC_Project_Activity',
        'label' => E::ts('EIC Project (activity)'),
        'data_type' => 'EntityReference',
        'html_type' => 'Autocomplete-Select',
        'fk_entity' => 'Activity',
        'is_view' => TRUE,
        'column_name' => 'eic_project_activity',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    // EIC project details copied from the matched EIC_Awardee_Project activity
    // (custom group EIC_Horizon_Europe_Project_information) at case creation, so
    // the onboarding case carries the project context. Free text snapshots.
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_Project_Funding',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'Project_Funding',
        'label' => E::ts('Funding'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'project_funding',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_Project_Category',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'Project_Category',
        'label' => E::ts('Category'),
        'html_type' => 'Select',
        'option_group_id.name' => 'EIC_Horizon_Europe_Project_Activity_Category',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'project_category',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_Project_Funding_Type',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'Project_Funding_Type',
        'label' => E::ts('Funding Type'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'project_funding_type',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Awardee_information_CustomField_Project_Cut_Off_Date',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_information',
        'name' => 'Project_Cut_Off_Date',
        'label' => E::ts('Cut-Off-Date'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'text_length' => 255,
        'column_name' => 'project_cut_off_date',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
