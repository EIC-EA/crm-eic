<?php
declare(strict_types=1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'nc_automations.civix.php';

// phpcs:enable

use CRM_NcAutomations_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function nc_automations_civicrm_config(\CRM_Core_Config $config): void
{
  _nc_automations_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function nc_automations_civicrm_install(): void
{
  _nc_automations_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function nc_automations_civicrm_enable(): void
{
  _nc_automations_civix_civicrm_enable();
}

/**
 * @param $formName
 * @param $form
 */
function nc_automations_civicrm_preProcess($formName, &$form): void
{
  if ($form instanceof CRM_Case_Form_CaseView) {
    if (property_exists($form, '_caseType') && $form->{'_caseType'} === 'eic_engagement') {

    }

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
  if ($objectName == 'CaseContact') {
    //triggers when contacts are added or deleted.
    if (is_a($objectRef, 'CRM_Case_DAO_CaseContact') && property_exists($objectRef, 'case_id')) {
      __nc_automations_case_roles_automate($objectRef->case_id);
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
