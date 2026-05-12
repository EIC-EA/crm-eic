<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'CustomField_Program',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Awardee_Organization',
        'name' => 'Program',
        'label' => E::ts('Program'),
        'html_type' => 'Text',
        'weight' => 2,
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
];
