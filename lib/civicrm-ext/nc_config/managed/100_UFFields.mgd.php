<?php
use CRM_NcConfig_ExtensionUtil as E;

return [
  [
    'name' => 'UFField_NewIndividual_ContactSubType',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'new_individual',
        'field_name' => 'contact_sub_type',
        'weight' => 4,
        'label' => E::ts('Contact Subtype'),
        'field_type' => 'Contact',
      ],
    ],
  ],
  [
    'name' => 'UFField_NewOrganisation_ContactSubType',
    'entity' => 'UFField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'uf_group_id.name' => 'new_organization',
        'field_name' => 'contact_sub_type',
        'weight' => 5,
        'label' => E::ts('Contact Subtype'),
        'field_type' => 'Contact',
      ],
    ],
  ],
];
