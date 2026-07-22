<?php
declare(strict_types = 1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'nc_automations.civix.php';
// phpcs:enable

use CRM_NcAutomations_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function nc_automations_civicrm_config(\CRM_Core_Config $config): void {
  _nc_automations_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function nc_automations_civicrm_install(): void {
  _nc_automations_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function nc_automations_civicrm_enable(): void {
  _nc_automations_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function _nc_automations_is_civirules_installed(): bool
{
  if (civicrm_api3('Extension', 'get', ['key' => 'civirules', 'status' => 'installed'])['count']) {
    return true;
  } elseif (civicrm_api3('Extension', 'get', ['key' => 'org.civicoop.civirules', 'status' => 'installed'])['count']) {
    return true;
  }
  return false;
}

/**
 * Implements hook civicrm_pre().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pre
 */
function nc_automations_civicrm_pre($op, $objectName, $objectId, &$params)
{
  //pre deleting individuals permanently
  if ($objectName === 'Individual' && $op === 'delete') {
    __nc_automations_hard_delete_individuals($objectId);
  }
  //pre restoring individuals from trash
  if ($objectName === 'Individual' && $op === 'edit') {
    __nc_automations_check_restore_and_update_relationships_individual($objectId, $params);
  }
}

/**
 * Helper function
 *
 * @param $objectId
 * @param $params
 * @return void
 */
function __nc_automations_check_restore_and_update_relationships_individual($objectId, $params)
{
  try {
    $ind = Civi\Api4\Contact::get()
      ->addWhere('id', '=', $objectId)
      ->execute()
      ->first();
    if ($ind) {
      if ($ind['is_deleted'] && !$params['is_deleted']) {
        $idToString = strval($ind['id']);
        $syncHelper = CRM_NcAutomations_Helper_OrganizationSyncHelper::getHelper();
        $syncHelper->restoreInvestorAtRelationshipFromDeletedIndividual($idToString);
        $syncHelper->syncOrganizationCustomFields($idToString);
      }

    }
  } catch (Exception $e) {
    Civi::log()->error($e->getMessage());
  }


}

/**
 * Helper function
 *
 * @param int $objectId
 * @return void
 */
function __nc_automations_hard_delete_individuals(int $objectId)
{

  try {
    $syncHelper = CRM_NcAutomations_Helper_OrganizationSyncHelper::getHelper();
    $ind = Civi\Api4\Contact::get()
      ->addWhere('id', '=', $objectId)
      ->execute()
      ->first();
    if ($ind) {
      $idToString = strval($ind['id']);
      if (in_array('Investor_Representative', $ind['contact_sub_type'])) {
        $syncHelper->invalidateInvestorAtRelationshipFromDeletedIndividual($idToString);
        $syncHelper->syncOrganizationCustomFields($idToString);
      }
    }
  } catch (Exception $e) {
    Civi::log()->error($e->getMessage());
  }

}



/**
 * Implements hook_civicrm_post
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function nc_automations_civicrm_post($op, $objectName, $objectId, &$objectRef)
{
    //check for case client autocomplete.
  if ($objectName == 'CaseContact') {
    //triggers when contacts are added or deleted.
    if (is_a($objectRef, 'CRM_Case_DAO_CaseContact') && property_exists($objectRef, 'case_id')) {
      __nc_automations_case_roles_automate($objectRef->case_id);
    }

  }
  //post trashing (soft delete) individual.
  if ($objectName === 'Individual' && $op === 'edit') {
    try {
      $ind = Civi\Api4\Contact::get()
        ->addWhere('id', '=', $objectId)
        ->execute()
        ->first();

      if ($ind) {
        if ($ind["is_deleted"]
          && in_array('Investor_Representative', $ind['contact_sub_type'])) {
          $syncHelper = CRM_NcAutomations_Helper_OrganizationSyncHelper::getHelper();
          $syncHelper->invalidateInvestorAtRelationshipFromDeletedIndividual($objectId);
          $syncHelper->syncOrganizationCustomFields($objectId);
        }
      }
    } catch (Exception $e) {
      Civi::log()->error($e->getMessage());
    }

  }
}

function __nc_automations_case_roles_automate($id)
{
  $engagementHelper = CRM_NcAutomations_EngagementCaseAutocompleteHelper::getInstance();
  try {
    $clients = $engagementHelper->getCaseClients($id);
    $case = $engagementHelper->getCase($id);
    if (array_key_exists('case_type_id:name', $case) && $case["case_type_id:name"] == "eic_engagement") {
      if (!empty($clients) && sizeof($clients) == 2) {
        $success = $engagementHelper->fillEngagementRoles($case, $clients);
        if (!$success) {
          //if automation fails for any reason inform the admin, and log the failure.
          $warning = 'Could not handle Investor and Awardee Roles automatically for this Engagement Case';
          CRM_Core_Session::setStatus(
            E::ts($warning),
            E::ts('Warning'),
            'alert'
          );
          Civi::log('EngagementCaseAutocomplete')->error($warning . '. CaseId :' . $id);
        }
      }
    }

  } catch (Exception $e) {
    //log potential errors
    Civi::log('EngagementCaseAutocomplete')->error($e->getMessage());
  }
}
