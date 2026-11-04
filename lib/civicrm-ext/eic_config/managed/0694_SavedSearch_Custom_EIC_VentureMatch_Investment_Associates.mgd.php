<?php
use CRM_EicConfig_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_Custom_EIC_VentureMatch_Investment_Associates',
    'entity' => 'SavedSearch',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Custom_EIC_VentureMatch_Investment_Associates',
        'label' => 'Custom - EIC VentureMatch Investment Associates',
        'api_entity' => 'Contact',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'sort_name',
            'contact_type:label',
            'contact_sub_type:label',
          ],
          'orderBy' => [],
          'where' => [
            [
              'groups:name',
              'IN',
              [
                'Investment_Assoc_19',
              ],
            ],
          ],
          'groupBy' => [],
          'join' => [],
          'having' => [],
        ],
        'description' => 'This search list the Investment Associates of the EIC VentureMatch Programme.
It display the users added to the Investment Associate group.
It is used in search form "Custom - EIC VentureMatch Service Request Follow-up" as a filter',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];