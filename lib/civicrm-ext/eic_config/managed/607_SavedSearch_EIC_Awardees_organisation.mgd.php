<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_EIC_Awardees_organisation',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'EIC_Awardees_organisation',
        'label' => E::ts('EIC Awardees organisations'),
        'api_entity' => 'Organization',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'sort_name',
            'contact_sub_type:label',
          ],
          'orderBy' => [],
          'where' => [
            [
              'contact_sub_type:name',
              'CONTAINS',
              ['EIC_Awardee'],
            ],
          ],
          'groupBy' => [],
          'join' => [],
          'having' => [],
        ],
      ],
      'match' => ['name'],
    ],
  ],
];
