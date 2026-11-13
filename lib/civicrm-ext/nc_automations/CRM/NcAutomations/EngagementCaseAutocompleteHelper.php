<?php

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\CaseContact;
use Civi\Api4\CiviCase;
use Civi\Api4\Contact;

class CRM_NcAutomations_EngagementCaseAutocompleteHelper {

  private static $instances = [];

  const EIC_AWARDEE = 'EIC_Awardee';
  const INVESTOR = 'Investor';
  const ANY = 'Any';

  /**
   * @return $this
   */
  protected function _construct() {
  }

  public static function getInstance(): CRM_NcAutomations_EngagementCaseAutocompleteHelper {
    $class = static::class;
    if (!isset(self::$instances[$class])) {
      self::$instances[$class] = new CRM_NcAutomations_EngagementCaseAutocompleteHelper();
    }

    return self::$instances[$class];
  }

  /**
   * Returns the case clients.
   *
   * @param int|string $caseId
   *   The case id.
   *
   * @return array
   *   Case clients array.
   */
  private function getCaseClients(int|string $caseId): array {
    // Load the case contacts.
    $caseContacts = \Civi\Api4\CaseContact::get(FALSE)
      ->addSelect('*', 'case_id.case_type_id:name', 'contact_id.contact_sub_type')
      ->addWhere('case_id.id', '=', $caseId)
      ->addWhere('case_id.case_type_id:name', '=', 'eic_engagement')
      ->execute();

    if (empty($caseContacts)) {
      return [];
    }

    // Array holding the clients.
    $clients = [];
    foreach ($caseContacts as $caseContact) {
      //get the important data from the db
      $subtype = $caseContact['contact_id.contact_sub_type'];
      if (empty($subtype)) {
        continue;
      }

      $subtype = sizeof($subtype) > 1 ? 'Any' : $subtype[0];
      if (isset($clients[$subtype])) {
        continue;
      }
      $clients[$subtype] = $caseContact['contact_id'];
    }

    // Apply business rules to produce exactly [EIC_Awardee => id, Investor => id].
    $result = [];

    if (isset($clients[self::EIC_AWARDEE], $clients[self::INVESTOR])) {
      // Both present directly.
      $result[self::EIC_AWARDEE] = $clients[self::EIC_AWARDEE];
      $result[self::INVESTOR] = $clients[self::INVESTOR];
    }
    elseif (isset($clients[self::EIC_AWARDEE], $clients[self::ANY])) {
      // EIC_Awardee stays, Any becomes Investor.
      $result[self::EIC_AWARDEE] = $clients[self::EIC_AWARDEE];
      $result[self::INVESTOR] = $clients[self::ANY];
    }
    elseif (isset($clients[self::INVESTOR], $clients[self::ANY])) {
      // Investor stays, Any becomes EIC_Awardee.
      $result[self::INVESTOR] = $clients[self::INVESTOR];
      $result[self::EIC_AWARDEE] = $clients[self::ANY];
    }

    // Must have both roles, otherwise return empty.
    if (!isset($result[self::EIC_AWARDEE]) || !isset($result[self::INVESTOR])) {
      return [];
    }

    // Return the result.
    return $result;
  }

  /**
   * Returns the case by the case ID.
   *
   * @param int|string $caseId
   *   The case Id.
   *
   * @return array|null
   *   The case or NULL if nothing is found.
   */
  public function getCase(int|string $caseId): ?array {
    return CiviCase::get(TRUE)
      ->addSelect('*', 'case_type_id:name', 'custom.*')
      ->addWhere('id', '=', $id)
      ->execute()
      ->first();
  }

  /**
   * Fill Engagement Roles.
   *
   * @param int|string $caseId
   *   The case id
   *
   * @return bool
   *   Return flag.
   */
  public function fillEngagementRoles(int|string $caseId): bool {
    // Get the case clients.
    $clients = $this->getCaseClients($caseId);
    if (empty($clients)) {
      // Update the case.
      $results = CiviCase::update(TRUE)
        ->addValue('Client_Roles.EIC_Awardee', NULL)
        ->addValue('Client_Roles.Investor', NULL)
        ->addWhere('id', '=', $caseId)
        ->execute();
      return FALSE;
    }

    try {
      // Update the case.
      $results = CiviCase::update(TRUE)
        ->addValue('Client_Roles.EIC_Awardee', $clients[self::EIC_AWARDEE])
        ->addValue('Client_Roles.Investor', $clients[self::INVESTOR])
        ->addWhere('id', '=', $caseId)
        ->execute();
    } catch (Exception $e) {
      Civi::log('EngagementCaseAutocompleteHelper')->error($e->getMessage());
      return FALSE;
    }
    return TRUE;
  }

}
