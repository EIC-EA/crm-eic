<?php

use CRM_EicAnonymiser_ExtensionUtil as E;

/**
 * EicAnonymiser.Anonymise API specification.
 *
 * @param array $spec description of fields supported by this API call
 */
function _civicrm_api3_eic_anonymiser_Anonymise_spec(&$spec) {
  $spec['contact_id'] = [
    'title'        => 'Contact ID',
    'description'  => 'ID of the contact to anonymise in place.',
    'type'         => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $spec['dry_run'] = [
    'title'        => 'Dry Run',
    'description'  => 'When set, nothing is written; the result previews the changes.',
    'type'         => CRM_Utils_Type::T_BOOLEAN,
    'api.default'  => 0,
  ];
}

/**
 * EicAnonymiser.Anonymise API.
 *
 * Anonymises a single contact in place (does not delete related records).
 * With dry_run=1 it reports the planned changes without writing anything.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws \CRM_Core_Exception
 */
function civicrm_api3_eic_anonymiser_Anonymise($params) {
  $contactId = (int) $params['contact_id'];
  $dryRun = !empty($params['dry_run']);
  $outcome = CRM_EicAnonymiser_Worker::anonymise($contactId, $dryRun);

  return civicrm_api3_create_success(
    [
      [
        'contact_id' => $contactId,
        'dry_run'    => $outcome['dry_run'],
        'anonymised' => !$outcome['dry_run'],
        'changes'    => $outcome['changes'],
        'log'        => $outcome['log'],
      ],
    ],
    $params,
    'EicAnonymiser',
    'Anonymise'
  );
}
