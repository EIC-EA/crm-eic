<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EIC_Organisation_identifiers',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Organisation_identifiers',
        'title' => E::ts('Organisation identifiers'),
        'extends' => 'Organization',
        'extends_entity_column_value' => ['EIC_Awardee', 'Investor'],
        'help_pre' => E::ts('<p>Unique identifiers used to identified the organisation</p>'),
        'weight' => 4,
        'collapse_adv_display' => TRUE,
        'is_public' => FALSE,
        'table_name' => 'civicrm_value_srm_org_ids'
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Organisation_identifiers_CustomField_SMEDId',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Organisation_identifiers',
        'name' => 'SMEDId',
        'label' => E::ts('SMEDId'),
        'html_type' => 'Text',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'smed_id'
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Organisation_identifiers_CustomField_PIC',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Organisation_identifiers',
        'name' => 'PIC',
        'label' => E::ts('PIC'),
        'html_type' => 'Text',
        'help_pre' => E::ts('The Participant Identification Code (PIC) is a unique 9-digit reference number used by the European Commission to identify a legal entity (company, university, research centre, public body, NGO, etc.) across all EU funding programmes, including European Innovation Council (EIC) calls, grants, prizes, and tenders.'),
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'in_selector' => TRUE,
        'column_name' => 'pic'
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
