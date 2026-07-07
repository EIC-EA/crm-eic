<?php

use Civi\Api4\Generic\Result;

class CRM_NcAutomations_Helper_OrganizationSyncHelper
{
  private static array $instance = [];

  protected function __construct()
  {

  }

  /**
   * Implements singleton pattern. Only one instance of this class is needed for all processes.
   */
  static public function getHelper(): CRM_NcAutomations_Helper_OrganizationSyncHelper
  {
    $class = static::class;
    if (!isset(self::$instance[$class])) {
      self::$instance[$class] = new static();
    }

    return self::$instance[$class];

  }

  /**
   * This method sets relationships Investor_at of deleted Individuals as inactive
   *
   * @param string $contactId
   * @return void
   */
  public function invalidateInvestorAtRelationshipFromDeletedIndividual(string $contactId)
  {
    $this->updateRelationship(intval($contactId), FALSE);
  }

  /**
   * @param string $contactId
   * @return void
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function syncOrganizationCustomFields(string $contactId): void
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
    } else {
      $organizationId = $entity['id'];
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

      \Civi::log('SyncInvestorRepresentativesHelper')->info("Sync Custom Fields for Investor with id: $organizationId ");

    } catch (CRM_Core_Exception $e) {
      \Civi::log('SyncInvestorRepresentativesHelper')->error($e->getMessage());
      //do rollback?
    }
  }

  /**
   * This method restores the inactive relationships Investor_at of Individuals restored form trash
   *
   * @param string $idToString
   * @return void
   */
  public function restoreInvestorAtRelationshipFromDeletedIndividual(string $contactId): void
  {
    $id = intval($contactId);
    $this->updateRelationship($id, TRUE);
  }

  private function updateRelationship(int $contactId, bool $newStatus): void
  {
    try {
      $relationships = \Civi\Api4\Relationship::get(TRUE)
        ->addWhere('relationship_type_id:name', '=', 'Investor at')
        ->addWhere('contact_id_a', '=', $contactId)
        ->execute();
      foreach ($relationships as $relationship) {
        $results = \Civi\Api4\Relationship::update(TRUE)
          ->addValue('is_active', $newStatus)
          ->addValue('id', $relationship['id'])
          ->addWhere('relationship_type_id:label', '=', 'Investor at')
          ->addWhere('contact_id_a', '=', $relationship['contact_id_a'])
          ->execute();
        foreach ($results as $result) {
          Civi::log('SyncInvestorRepresentativesHelper')
            ->info('removed relationship Investor_at for individual: ' . $relationship['contact_id_a']);
        }
      }

    } catch (Exception $e) {
      \Civi::log('SyncInvestorRepresentativesHelper')->error($e->getMessage());
    }
  }
}
