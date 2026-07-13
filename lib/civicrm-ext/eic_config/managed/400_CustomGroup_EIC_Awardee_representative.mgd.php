<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_EIC_Awardee_representative',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardee_representative',
        'title' => E::ts('EIC Awardee representative'),
        'extends' => 'Individual',
        'extends_entity_column_value' => ['EIC_Registered'],
        'weight' => 11,
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_eic_awardee_representative',
      ],
      'match' => ['name'],    
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Awardee_representative_CustomField_eulogin',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_representative',
        'name' => 'eulogin',
        'label' => E::ts('eulogin'),
        'html_type' => 'Text',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'funds_vintage_year',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
