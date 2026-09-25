<?php
use CRM_EicEuSurveyFormProcessor_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_eic_sr_innonext_data',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'eic_sr_innonext_data',
        'title' => E::ts('EIC InnoNext Service Request'),
        'extends' => 'Case',
        'extends_entity_column_value:name' => ['eic_sr_innonext'],
        'collapse_adv_display' => TRUE,
        'table_name' => 'civicrm_value_srm_sr_innonext',
      ],
      'match' => ['name'],
    ],
  ],
];
