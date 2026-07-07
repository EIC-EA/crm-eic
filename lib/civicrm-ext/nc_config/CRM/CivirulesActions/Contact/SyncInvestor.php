<?php

use Civi\Api4\Generic\Result;


class CRM_CivirulesActions_Contact_SyncInvestor extends CRM_Civirules_Action
{

  public function getExtraDataInputUrl($ruleActionId)
  {
    return FALSE;
  }

  public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData)
  {

    $contId = $triggerData->getContactId();
    // Find organization
    $entity = \Civi\Api4\Contact::get()
      ->addWhere('id', '=', $contId)
      ->execute()
      ->first();
    if ($entity['contact_type'] == 'Individual') {
      $organization = $this->getOrganization($contId);
      if (!$organization) {
        return;
      }
      $organizationId = $organization['r.contact_id_b'];
    } else {
      $organizationId = $entity['id'];
    }

    // Get all representatives
    $represenativesWithCustomFields = \Civi\Api4\Contact::get(TRUE)
      ->addSelect('id', 'contact_type', 'contact_sub_type', 'Investment_Related.Preferred_Stages', 'Investment_Related.Relevant_Verticals', 'Investment_Related.Geographical_Focus', 'rep.Investment_Focus.Preferred_Stages', 'rep.Investment_Focus.Relevant_Verticals', 'rep.Investment_Focus.Geographical_Focus', 'rep.contact_sub_type', 'rep.id')
      ->addJoin('Relationship AS r', 'INNER', ['r.contact_id_b', '=', 'id'])
      ->addJoin('Contact AS rep', 'INNER', ['rep.id', '=', 'r.contact_id_a'])
      ->addWhere('id', '=', $organizationId)
      ->addWhere('contact_type', '=', 'Organization')
      ->addWhere('contact_sub_type', '=', 'Investor')
      ->addWhere('rep.contact_sub_type', '=', 'Investor_Representative')
      ->execute();

    // Aggregate values
    $verticals = [];
    $geo = [];
    $stages = [];
    $this->aggregateCustomFields($represenativesWithCustomFields, $verticals, $geo, $stages);

    // Update organization with unique values
    $this->updateOrganization($organizationId, $verticals, $geo, $stages);


  }

  /*
 * Get the associated Investor Organization of the Representative
 */
  private function getOrganization(int $representativeId)
  {
    $organizations = \Civi\Api4\Contact::get(TRUE)
      ->addSelect('id', 'r.contact_id_b')
      ->addJoin('Relationship AS r', 'INNER', ['r.contact_id_a', '=', 'id'])
      ->addWhere('id', '=', $representativeId)
      ->addOrderBy('id', 'ASC')
      ->setLimit(5)
      ->execute();
// Get the results from the Api4\Generic\Result Class
    $organizations = $organizations->getArrayCopy();
    if (empty($organizations)) {
      //exception
    }
    if (sizeof($organizations) > 1) {
      //exception
    }
    return end($organizations);
  }

  /*
   * Performs functions on array references
   */
  private function aggregateCustomFields(
    Result $represenativesWithCustomFields,
    array  &$verticals,
    array  &$geo,
    array  &$stages)
  {
    foreach ($represenativesWithCustomFields as $rep) {
      if ($rep['rep.Investment_Focus.Relevant_Verticals']) {
        $verticals = array_merge($rep['rep.Investment_Focus.Relevant_Verticals'], $verticals);
      }
      if ($rep['rep.Investment_Focus.Preferred_Stages']) {
        $stages = array_merge($rep['rep.Investment_Focus.Preferred_Stages'], $stages);
      }
      if ($rep['rep.Investment_Focus.Geographical_Focus']) {
        $geo = array_merge($rep['rep.Investment_Focus.Geographical_Focus'], $geo);
      }
    }

  }

  private function updateOrganization
  (
    int   $organizationId,
    array $verticals,
    array $geo,
    array $stages)
  {
    try {

      $results = \Civi\Api4\Contact::update(TRUE);
      if ($stages) {
        $results->addValue('Investment_Related.Preferred_Stages', array_values(array_unique($stages)));
      }
      if ($verticals) {
        $results->addValue('Investment_Related.Relevant_Verticals', array_values(array_unique($verticals)));
      }
      if ($geo) {
        $results->addValue('Investment_Related.Geographical_Focus', array_values(array_unique($geo)));
      }
      $results->addWhere('contact_type', '=', 'Organization')
        ->addWhere('contact_sub_type', '=', 'Investor')
        ->addWhere('id', '=', $organizationId)
        ->execute();

      \Civi::log('SyncInvestorRepresentatives')->info("Sync Custom Fields for Investor with id: $organizationId ");

    } catch (CRM_Core_Exception $e) {
      \Civi::log('SyncInvestorRepresentatives')->error($e->getMessage());
      //do rollback?
    }
  }
}
