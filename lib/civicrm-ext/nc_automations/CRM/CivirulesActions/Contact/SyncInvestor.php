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
    $this->syncOrganizationCustomFields($contId);
  }

  /**
   * @param string $contactId
   * @return void
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  private function syncOrganizationCustomFields(string $contactId): void
  {
    $entity = \Civi\Api4\Contact::get()
      ->addWhere('id', '=', $contactId)
      ->execute()
      ->first();
    if ($entity['contact_type'] == 'Individual') {
      $organization = $this->getOrganization($contactId);
      if (!$organization) {
        return;
      }
      $organizationId = $organization['r.contact_id_b'];
      Civi::log('Test')->critical('organizationId from Relationship: ' . $organizationId);
    } else {
      $organizationId = $entity['id'];
      Civi::log('Test')->critical('organizationId from Entity: ' . $organizationId);
    }

    // Get all representatives
    $represenativesWithCustomFields = \Civi\Api4\Contact::get(TRUE)
      ->addSelect('id', 'contact_type', 'contact_sub_type', 'General_Company_Info.Preferred_Stages', 'General_Company_Info.Relevant_Verticals', 'General_Company_Info.Geographical_Focus', 'rep.Investment_Focus.Preferred_Stages', 'rep.Investment_Focus.Relevant_Verticals', 'rep.Investment_Focus.Geographical_Focus', 'rep.contact_sub_type', 'rep.id')
      ->addJoin('Relationship AS r', 'INNER', ['r.contact_id_b', '=', 'id'])
      ->addJoin('Contact AS rep', 'INNER', ['rep.id', '=', 'r.contact_id_a'])
      ->addWhere('id', '=', $organizationId)
      ->addWhere('contact_type', '=', 'Organization')
      ->addWhere('contact_sub_type', '=', 'Investor')
      ->addWhere('rep.contact_sub_type', '=', 'Investor_Representative')
      //where the relationship is active otherwise deleted contacts still count
      ->addWhere('r.is_active', '=', TRUE)
      ->execute();
    Civi::log('Test')->critical('Representatives: ' . json_encode($represenativesWithCustomFields));
    // Aggregate values
    $verticals = [];
    $geo = [];
    $stages = [];
    $this->aggregateCustomFields($represenativesWithCustomFields, $verticals, $geo, $stages);

    // Update organization with unique values
    $this->updateOrganization($organizationId, $verticals, $geo, $stages);

  }

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
        $results = $results->addValue('General_Company_Info.Preferred_Stages', array_values(array_unique($stages)));
      } else {
        $results = $results->addValue('General_Company_Info.Preferred_Stages', NULL);
      }
      if ($verticals) {
        $results = $results->addValue('General_Company_Info.Relevant_Verticals', array_values(array_unique($verticals)));
      } else {
        $results = $results->addValue('General_Company_Info.Relevant_Verticals', NULL);
      }
      if ($geo) {
        $results = $results->addValue('General_Company_Info.Geographical_Focus', array_values(array_unique($geo)));
      } else {
        $results = $results->addValue('General_Company_Info.Geographical_Focus', NULL);
      }
      $results = $results->addWhere('contact_type', '=', 'Organization')
        ->addWhere('contact_sub_type', '=', 'Investor')
        ->addWhere('id', '=', $organizationId)
        ->execute();

      \Civi::log('SyncInvestorAction')->info("Sync Custom Fields for Investor with id: $organizationId ");

    } catch (CRM_Core_Exception $e) {
      \Civi::log('SyncInvestorAction')->error($e->getMessage());
      //do rollback?
    }
  }
}
