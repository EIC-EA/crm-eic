<?php
use CRM_EicConfig_ExtensionUtil as E;

$subtypeIds = \Civi\Api4\RelationshipType::get()
    ->setCheckPermissions(false)
    ->addSelect('id')
    ->addWhere('name_a_b', 'IN', ['EIC_Coco_For', 'EIC Project member of', 'EIC_LEAR_For', 'EIC_PaCo_For', 'EIC_PCoco_For'])
    ->execute()
    ->column('id');

return [
  [
    'name' => 'CustomGroup_EIC_Horizon_europe_Relationship',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Horizon_europe_Relationship',
        'title' => E::ts('Horizon europe Relationship'),
        'extends' => 'Relationship',
        'extends_entity_column_value' => $subtypeIds,
        'weight' => 8,
        'collapse_adv_display' => TRUE,
        'is_public' => FALSE,
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_EIC_Horizon_europe_Relationship_CustomField_Horizon_Europe_Project',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'EIC_Horizon_europe_Relationship',
        'name' => 'Horizon_Europe_Project',
        'label' => E::ts('Horizon Europe Project'),
        'data_type' => 'EntityReference',
        'html_type' => 'Autocomplete-Select',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'fk_entity' => 'Activity',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
