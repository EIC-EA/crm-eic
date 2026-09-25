<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

// Reusable "main contact data" snapshot stored ON the EU-Survey activity, so the
// activity is a complete record of the survey response. The contact data is
// common to all onboarding surveys, so this group is deliberately generic (not
// per-survey): future survey activity types should be added to
// 'extends_entity_column_value:name' rather than duplicating the fields.
// Collapsed on initial display.
return [
  [
    'name' => 'CustomGroup_srm_survey_contact_snapshot',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'srm_survey_contact_snapshot',
        'title' => E::ts('Survey - Main Contact Data'),
        'extends' => 'Activity',
        'extends_entity_column_value:name' => ['eic_accelerator_onboarding_survey'],
        'collapse_display' => TRUE,
        'weight' => 21,
        'table_name' => 'civicrm_value_srm_survey_contact_snap',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_srm_survey_contact_snapshot_CustomField_First_Name',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'srm_survey_contact_snapshot',
        'name' => 'First_Name',
        'label' => E::ts('First name'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'first_name',
      ],
      'match' => ['name', 'custom_group_id'],
    ],
  ],
  [
    'name' => 'CustomGroup_srm_survey_contact_snapshot_CustomField_Last_Name',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'srm_survey_contact_snapshot',
        'name' => 'Last_Name',
        'label' => E::ts('Last name'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'last_name',
      ],
      'match' => ['name', 'custom_group_id'],
    ],
  ],
  [
    'name' => 'CustomGroup_srm_survey_contact_snapshot_CustomField_Professional_Email',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'srm_survey_contact_snapshot',
        'name' => 'Professional_Email',
        'label' => E::ts('Professional email'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'professional_email',
      ],
      'match' => ['name', 'custom_group_id'],
    ],
  ],
  [
    'name' => 'CustomGroup_srm_survey_contact_snapshot_CustomField_Phone_Number',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'srm_survey_contact_snapshot',
        'name' => 'Phone_Number',
        'label' => E::ts('Phone number'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'phone_number',
      ],
      'match' => ['name', 'custom_group_id'],
    ],
  ],
  [
    'name' => 'CustomGroup_srm_survey_contact_snapshot_CustomField_Role_In_Organisation',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'srm_survey_contact_snapshot',
        'name' => 'Role_In_Organisation',
        'label' => E::ts('Role in the organisation'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'role_in_organisation',
      ],
      'match' => ['name', 'custom_group_id'],
    ],
  ],
];
