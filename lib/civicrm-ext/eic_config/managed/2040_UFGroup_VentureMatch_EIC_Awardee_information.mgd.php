<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'UFGroup_VentureMatch_EIC_Awardee_information',
    'entity' => 'UFGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'VentureMatch_EIC_Awardee_information',
        'group_type' => ['Organization', 'Contact', 'EIC_Awardee'],
        'title' => E::ts('VentureMatch EIC Awardee information'),
        'frontend_title' => E::ts('VentureMatch EIC Awardee information'),
        'is_update_dupe' => TRUE,
        'created_date' => '2026-10-22 12:31:51',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'UFGroup_VentureMatch_EIC_Awardee_information_UFField_1',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'VentureMatch_EIC_Awardee_information',
        'field_name' => 'organization_name',
        'label' => E::ts('Organization Name'),
        'field_type' => 'Organization',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_VentureMatch_EIC_Awardee_information_UFField_2',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'VentureMatch_EIC_Awardee_information',
        'field_name' => 'country',
        'label' => E::ts('Country'),
        'field_type' => 'Contact',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_VentureMatch_EIC_Awardee_information_UFField_3',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'VentureMatch_EIC_Awardee_information',
        'field_name:name' => 'EU_Survey_Company_Data.Sector',
        'is_searchable' => TRUE,
        'label' => E::ts('Sector'),
        'field_type' => 'Organization',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_VentureMatch_EIC_Awardee_information_UFField_4',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'VentureMatch_EIC_Awardee_information',
        'field_name' => 'url',
        'website_type_id:name' => 'Work',
        'label' => E::ts('Website'),
        'field_type' => 'Contact',
      ],
    ],
  ],
  [
    'name' => 'UFGroup_VentureMatch_EIC_Awardee_information_UFField_5',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'VentureMatch_EIC_Awardee_information',
        'field_name' => 'custom_92',
        'help_post' => E::ts('From the EIC VM Fundraising Assessment EU-Survey.'),
        'label' => E::ts('Dealroom Profile'),
        'field_type' => 'EIC_Awardee',
      ],
    ],
  ],
];
