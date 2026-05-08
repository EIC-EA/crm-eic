<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomField_PIC',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_Organization',
        'name' => 'PIC',
        'label' => E::ts('PIC'),
        'data_type' => 'Int',
        'html_type' => 'Text',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'pic_1',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
