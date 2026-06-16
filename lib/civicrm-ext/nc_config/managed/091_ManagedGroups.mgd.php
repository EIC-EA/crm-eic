<?php

use CRM_NcConfig_ExtensionUtil as E;

$groups = \Civi\Api4\Group::get(FALSE)
  ->addSelect('children', 'id')
  ->addWhere('title', '=', 'Investor Type')
  ->setLimit(25)
  ->execute()->first();
CRM_Core_Error::debug_var('caseTypeId', $groups);
$parentId = $groups['id'];
$parentChildren = $groups['children'];

return [
  [
    'name' => 'Group_Business_A_3',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Business_A_3',
        'title' => E::ts('Business Angel'),
        'description' => E::ts('Business Angel'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Business Angel'),
        'frontend_description' => E::ts('Business Angel'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_Corporate_Venture_Cap_4',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Corporate_Venture_Cap_4',
        'title' => E::ts('Corporate Venture Capital'),
        'description' => E::ts('Corporate Venture Capital'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Corporate Venture Capital'),
        'frontend_description' => E::ts('Corporate Venture Capital'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_Family_Of_5',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Family_Of_5',
        'title' => E::ts('Family Office'),
        'description' => E::ts('Family Office'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Family Office'),
        'frontend_description' => E::ts('Family Office'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_IPO_Adv_6',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'IPO_Adv_6',
        'title' => E::ts('IPO Advisor'),
        'description' => E::ts('IPO Advisor'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('IPO Advisor'),
        'frontend_description' => E::ts('IPO Advisor'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_M_A_Adv_7',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'M_A_Adv_7',
        'title' => E::ts('M&A Advisor'),
        'description' => E::ts('M&A Advisor'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('M&A Advisor'),
        'frontend_description' => E::ts('M&A Advisor'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_Not_classifi_8',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Not_classifi_8',
        'title' => E::ts('Not classifiable'),
        'description' => E::ts('Not classifiable'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Not classifiable'),
        'frontend_description' => E::ts('Not classifiable'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_Private_Eq_9',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Private_Eq_9',
        'title' => E::ts('Private Equity'),
        'description' => E::ts('Private Equity'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Private Equity'),
        'frontend_description' => E::ts('Private Equity'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_Venture_Cap_10',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Venture_Cap_10',
        'title' => E::ts('Venture Capital'),
        'description' => E::ts('Venture Capital'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Venture Capital'),
        'frontend_description' => E::ts('Venture Capital'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_Venture_Debt_Prov_11',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Venture_Debt_Prov_11',
        'title' => E::ts('Venture Debt Provider'),
        'description' => E::ts('Venture Debt Provider'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Venture Debt Provider'),
        'frontend_description' => E::ts('Venture Debt Provider'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_O_12',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'O_12',
        'title' => E::ts('Other'),
        'description' => E::ts('Other'),
        'group_type' => [],
        'parents' => [$parentId],
        'frontend_title' => E::ts('Other'),
        'frontend_description' => E::ts('Other'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'Group_Investor_13',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Investor__13',
        'title' => E::ts('Investor Type'),
        'description' => E::ts('Investor Type - Contains Multiple Investor Types'),
        'children' => $parentChildren,
        'frontend_title' => E::ts('Investor Type'),
        'frontend_description' => E::ts('Investor Type'),
      ],
      'match' => ['name'],
    ],
  ],
];
