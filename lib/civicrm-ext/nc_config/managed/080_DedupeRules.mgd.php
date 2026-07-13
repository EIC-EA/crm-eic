<?php

use CRM_NcConfig_ExtensionUtil as E;

// Get the domain field.
$domainField = \Civi\Api4\CustomField::get(FALSE)
  ->addSelect('custom_group_id.table_name', 'column_name')
  ->addWhere('label', '=', 'Company Domain Name')
  ->execute()
  ->first();

// Domain group fieldset.
$table = $domainField['custom_group_id.table_name'];

// Domain field.
$column = $domainField['column_name'];

return [
  [
    'name' => 'DedupeRuleGroup_Organization_Domain',
    'entity' => 'DedupeRuleGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'contact_type' => 'Organization',
        'threshold' => 10,
        'used' => 'General',
        'name' => 'Organization_Domain',
        'title' => E::ts('Organization Domain'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'DedupeRuleGroup_Organization_Domain_DedupeRule_1',
    'entity' => 'DedupeRule',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'dedupe_rule_group_id.name' => 'Organization_Domain',
        'rule_table' => $table,
        'rule_field' => $column,
        'rule_weight' => 10,
      ],
    ],
  ],
  [
    'name' => 'DedupeRuleGroup_First_and_Last_Name',
    'entity' => 'DedupeRuleGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'contact_type' => 'Individual',
        'threshold' => 20,
        'used' => 'General',
        'name' => 'First_and_Last_Name',
        'title' => E::ts('First and Last Name'),
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'DedupeRuleGroup_First_and_Last_Name_DedupeRule_1',
    'entity' => 'DedupeRule',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'dedupe_rule_group_id.name' => 'First_and_Last_Name',
        'rule_table' => 'civicrm_contact',
        'rule_field' => 'first_name',
        'rule_weight' => 10,
      ],
    ],
  ],
  [
    'name' => 'DedupeRuleGroup_First_and_Last_Name_DedupeRule_2',
    'entity' => 'DedupeRule',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'dedupe_rule_group_id.name' => 'First_and_Last_Name',
        'rule_table' => 'civicrm_contact',
        'rule_field' => 'last_name',
        'rule_weight' => 10,
      ],
    ],
  ],
];
