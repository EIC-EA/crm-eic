<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'UFGroup_Custom_EIC_Awardee_Organisation_information_right',
    'entity' => 'UFGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Custom_EIC_Awardee_Organisation_information_right',
        'group_type' => ['Contact', 'EIC_Awardee', 'Investor'],
        'title' => E::ts('Custom - EIC Awardee Organisation information right'),
        'frontend_title' => E::ts('ID'),
        'is_update_dupe' => TRUE,
        'created_date' => '2026-10-21 12:47:29',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'UFGroup_Custom_EIC_Awardee_Organisation_information_right_UFField_1',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'Custom_EIC_Awardee_Organisation_information_right',
        'field_name' => 'contact_sub_type',
        'label' => E::ts('Contact Subtype'),
        'field_type' => 'Contact',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_Custom_EIC_Awardee_Organisation_information_right_UFField_2',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'Custom_EIC_Awardee_Organisation_information_right',
        'field_name' => 'custom_35',
        'label' => E::ts('Program'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_Custom_EIC_Awardee_Organisation_information_right_UFField_3',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'Custom_EIC_Awardee_Organisation_information_right',
        'field_name' => 'custom_34',
        'label' => E::ts('PIC'),
        'field_type' => 'Investor',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_Custom_EIC_Awardee_Organisation_information_right_UFField_4',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'Custom_EIC_Awardee_Organisation_information_right',
        'field_name' => 'custom_72',
        'help_pre' => E::ts('The company\'s LinkedIn profile URL.'),
        'label' => E::ts('LinkedIn Page'),
        'field_type' => 'Investor',
      ],
    ],
  ],
];
