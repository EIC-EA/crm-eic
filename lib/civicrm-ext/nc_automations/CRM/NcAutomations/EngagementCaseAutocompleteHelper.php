<?php

class CRM_NcAutomations_EngagementCaseAutocompleteHelper
{
  private static $instances = [];

  private const CLIENT_ROLES_CASE = [
    'investor-awardee' => 1,
    'awardee-only-investor-both' => 2,
    'investor-only-awardee-both' => 3,
    'both-have-both' => 0,
    'cannot-handle' => -1
  ];

  /**
   * @return $this
   */
  protected function _construct(): static
  {
    return $this;
  }

  public static function getInstance(): CRM_NcAutomations_EngagementCaseAutocompleteHelper
  {
    $class = static::class;
    if (!isset(self::$instances[$class])) {
      self::$instances[$class] = new static();
    }

    return self::$instances[$class];
  }

  /**
   * @param int|string $caseId
   * @return array
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function getCaseClients(int|string $caseId): array
  {
    $caseContacts = \Civi\Api4\CaseContact::get(TRUE)
      ->addSelect('*', 'case.case_type_id:name')
      ->addJoin('Case AS case', 'LEFT', ['case_id', '=', 'case.id'])
      ->addWhere('case_id', '=', $caseId)
      ->addWhere('case.case_type_id:name', '=', 'eic_engagement')
      ->setLimit(25)
      ->execute();

    $clients = [];
    foreach ($caseContacts as $caseContact) {
      //get the important data from the db
      $contactId = $caseContact['contact_id'];
      $clients[$contactId] = \Civi\Api4\Contact::get(TRUE)
        ->addSelect('id', 'contact_type', 'contact_sub_type', 'organization_name')
        ->addWhere('id', '=', $contactId)
        ->execute()
        ->first();
    }
    //have at least 2 clients
    if (!empty($clients) && sizeof($clients) > 1) {
      return $clients;
    }

    return [];
  }

  /**
   * @param int|string $caseId
   * @return array|null
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public
  function getCase(int|string $caseId): ?array
  {
    return $this->getCaseFromId($caseId);
  }

  /**
   * @param int|string $id
   * @return array|null
   * @throws CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  private
  function getCaseFromId(int|string $id): ?array
  {
    return \Civi\Api4\CiviCase::get(TRUE)
      ->addSelect('*', 'case_type_id:name', 'custom.*')
      ->addWhere('id', '=', $id)
      ->execute()
      ->first();
  }

  /**
   * @param array $case
   * @param array $clients
   * @return bool
   */
  public
  function fillEngagementRoles(array $case, array $clients): bool
  {

    $roleCase = $this->assertClientRoles($clients);

    if ($roleCase <= 0) {
      return false;
    }

    $clientsByRole = $this->sortClientsInRoles($clients);

    switch ($roleCase) {
      case self::CLIENT_ROLES_CASE['investor-awardee']:
        return $this->fillEngagementInvestorAwardee($clientsByRole['Investor'][0], $clientsByRole['EIC_Awardee'][0], $case);
      case self::CLIENT_ROLES_CASE['awardee-only-investor-both']:
        $investorId = array_values(array_diff($clientsByRole['Investor'], $clientsByRole['EIC_Awardee']));
        return $this->fillEngagementInvestorAwardee($investorId[0], array_values($clientsByRole['EIC_Awardee'])[0], $case);
      case self::CLIENT_ROLES_CASE['investor-only-awardee-both']:
        $awardeeId = array_values(array_diff($clientsByRole['EIC_Awardee'], $clientsByRole['Investor']));
        return $this->fillEngagementInvestorAwardee(array_values($clientsByRole['Investor'])[0], $awardeeId[0], $case);
    }

    //in any other case
    return FALSE;
  }

  private
  function assertClientRoles(array $clients): int
  {
    //if more than 2 clients in case
    if (empty($clients) || sizeof($clients) > 2) {
      return self::CLIENT_ROLES_CASE['cannot-handle'];
    }
    $roles = [];
    $roles = $this->sortClientsInRoles($clients);

    if (array_key_exists('Investor', $roles) && array_key_exists('EIC_Awardee', $roles)) {
      switch ($roles) {
        case $this->assertCaseInvestorAwardee($roles) :
          return self::CLIENT_ROLES_CASE['investor-awardee'];
          break;
        case $this->assertCaseInvestorOneAwardeeTwo($roles) :
          return self::CLIENT_ROLES_CASE['investor-only-awardee-both'];
          break;
        case $this->assertCaseInvestorTwoAwardeeOne($roles) :
          return self::CLIENT_ROLES_CASE['awardee-only-investor-both'];
          break;
        case $this->assertCaseBothHaveBothRoles($roles) :
          return self::CLIENT_ROLES_CASE['both-have-both'];
          break;
      }
    }
    return self::CLIENT_ROLES_CASE['cannot-handle'];
  }

  /**
   * Asserts if there is only One Investor and One Awardee
   *
   * @param array $roles
   * @return bool
   */
  private
  function assertCaseInvestorAwardee(array $roles): bool
  {
    return sizeof($roles['Investor']) == sizeof($roles['EIC_Awardee']) && sizeof($roles['Investor']) == 1;
  }

  /**
   * Asserts if there is only One Investor and Two Awardees
   *
   * @param array $roles
   * @return bool
   */
  private
  function assertCaseInvestorOneAwardeeTwo(array $roles): bool
  {
    return sizeof($roles['Investor']) < sizeof($roles['EIC_Awardee']) && sizeof($roles['Investor']) == 1;
  }

  /**
   * Asserts if there is only Two Investors and Exactly One Awardee
   *
   * @param array $roles
   * @return bool
   */
  private
  function assertCaseInvestorTwoAwardeeOne(array $roles): bool
  {
    return sizeof($roles['Investor']) > sizeof($roles['EIC_Awardee']) && sizeof($roles['EIC_Awardee']) == 1;
  }

  /**
   * Asserts if both users are both Investors and Awardees
   *
   * @param array $roles
   * @return bool
   */
  private
  function assertCaseBothHaveBothRoles(array $roles): bool
  {
    return sizeof($roles['Investor']) == sizeof($roles['EIC_Awardee']) && sizeof($roles['EIC_Awardee']) == 2;
  }

  /**
   * Organizes an array of client roles.
   * The structure of the array is role_key => [aClientIdWithRoleKey, anotherClientIdWithRoleKey]
   *
   * @param array $clients
   * @return array
   */
  private
  function sortClientsInRoles(array $clients): array
  {
    $roles = [];
    foreach ($clients as $client) {
      if (array_key_exists('contact_sub_type', $client)) {
        foreach ($client['contact_sub_type'] as $subType) {
          $roles[$subType][] = $client['id'];
        }
      }
    }
    return $roles;
  }

  private function fillEngagementInvestorAwardee(int|string $investorId, int|string $awardeeId, array $case)
  {
    $caseId = $case['id'];

    try {
      $results = \Civi\Api4\CiviCase::update(TRUE)
        ->addValue('Client_Roles.EIC_Awardee', $awardeeId)
        ->addValue('Client_Roles.Investor', $investorId)
        ->addWhere('id', '=', $caseId)
        ->execute();
      if ($results) {
        return TRUE;
      }
    } catch (\Exception $e) {
      Civi::log('EngagementCaseAutocompleteHelper')->error($e->getMessage());
      return FALSE;
    }
    return FALSE;
  }

}
