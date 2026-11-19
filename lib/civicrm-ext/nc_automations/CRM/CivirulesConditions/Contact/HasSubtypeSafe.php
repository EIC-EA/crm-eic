<?php

/**
 * Null-safe variant of CRM_CivirulesConditions_Contact_HasSubtype.
 *
 * The stock "contact_has_subtype" condition calls $triggerData->getContactId()
 * and feeds the result straight into CRM_Contact_BAO_Contact::getContactSubType(),
 * which ends up in CRM_Core_DAO::getFieldValue() with an empty search value and
 * throws "getFieldValue failed". That happens whenever the condition is evaluated
 * on a trigger whose TriggerData has no resolvable contact id — e.g. the generic
 * "Website / edit" trigger (rule SyncDomainOnWebsiteChange) during bulk imports,
 * where it produced thousands of error-log lines per run.
 *
 * This subclass returns FALSE (condition simply does not match) instead of
 * throwing when there is no contact to evaluate. Behaviour is otherwise identical
 * to the parent for any trigger that does supply a contact id.
 */
class CRM_CivirulesConditions_Contact_HasSubtypeSafe extends CRM_CivirulesConditions_Contact_HasSubtype {

  /**
   * @param CRM_Civirules_TriggerData_TriggerData $triggerData
   * @return bool
   */
  public function isConditionValid(CRM_Civirules_TriggerData_TriggerData $triggerData) {
    if (empty($triggerData->getContactId())) {
      // No contact in scope: nothing to match, and evaluating the parent here
      // would throw "getFieldValue failed". Treat as "does not apply".
      return FALSE;
    }
    return parent::isConditionValid($triggerData);
  }

  /**
   * Reuse the parent's extra-data form so the condition stays editable in the UI.
   *
   * @param int $ruleConditionId
   * @return bool|string
   */
  public function getExtraDataInputUrl($ruleConditionId) {
    return $this->getFormattedExtraDataInputUrl('civicrm/civirule/form/condition/contact_hassubtype', $ruleConditionId);
  }

}
