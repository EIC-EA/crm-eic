<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_international_trade_fairs_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_international_trade_fairs_data',
        'title' => E::ts('EIC International Trade Fairs Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_international_trade_fairs'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_trade_fairs',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_international_trade_fairs_data_CustomField_region',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_international_trade_fairs_data',
        'name' => 'region',
        'label' => E::ts('Which region?'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'region',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_eic_sr_international_trade_fairs_data_CustomField_type_of_events',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'eic_sr_international_trade_fairs_data',
        'name' => 'type_of_events',
        'label' => E::ts('Type of events?'),
        'html_type' => 'Text',
        'text_length' => 255,
        'column_name' => 'type_of_events',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
