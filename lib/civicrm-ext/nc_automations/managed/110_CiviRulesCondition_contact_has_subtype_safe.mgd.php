<?php
use CRM_NcAutomations_ExtensionUtil as E;

/**
 * Null-safe "Contact has subtype" condition.
 *
 * Registers CRM_CivirulesConditions_Contact_HasSubtypeSafe so it can be used by
 * rules (notably SyncDomainOnWebsiteChange) whose trigger may not resolve a
 * contact id. See the class docblock for the rationale.
 */
return [
  [
    'name' => 'CiviRulesCondition_contact_has_subtype_safe',
    'entity' => 'CiviRulesCondition',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'contact_has_subtype_safe',
        'label' => E::ts('Contact is (not) of Subtype(s) [null-safe]'),
        'class_name' => 'CRM_CivirulesConditions_Contact_HasSubtypeSafe',
        'is_active' => TRUE,
      ],
      'match' => ['name'],
    ],
  ],
];
