<?php

class CRM_NcAutomations_EngagementCaseAutocompleteHelper
{
    private static $instances = [];


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
        if (!empty($clients) && sizeof($clients) == 2) {
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

        if (!$roleCase) {
            return false;
        }

        $clientsByRole = $this->sortClientsInRoles($clients);
        /**
         *
         * This array should have a combination of two keys from those bellow
         * and two ids in any place
         * [
         * 'Investor' => [$investorId1, $investorId2, ...],
         * 'EIC_Awardee' => [$awardeeId1, $awardeeId2, ...],
         * 'EIC_Awardee_Investor' => [$awardeeId1, $awardeeId2, ...],
         * ];
         *
         * for example:
         *
         * [
         * 'Investor' => [$investorId1],
         * 'EIC_Awardee_Investor' => [$awardeeId1],
         * ];
         *
         * In the best case scenario there will be only one id under Investor and one under Awardee.
         *
         * More complex cases can exist though as there is no limit to the clients of the case or the
         * roles the Contacts can have.
         *
         * */
        switch ($roleCase) {
            case sizeof($clientsByRole['Investor']) == sizeof($clientsByRole['EIC_Awardee']):
                return $this->fillEngagementInvestorAwardee($clientsByRole['Investor'][0], $clientsByRole['EIC_Awardee'][0], $case);
            case sizeof($clientsByRole['Investor']) == sizeof($clientsByRole['EIC_Awardee_Investor']):
                return $this->fillEngagementInvestorAwardee($clientsByRole['Investor'][0], $clientsByRole['EIC_Awardee_Investor'][0], $case);
            case sizeof($clientsByRole['EIC_Awardee']) == sizeof($clientsByRole['EIC_Awardee_Investor']):
                return $this->fillEngagementInvestorAwardee($clientsByRole['EIC_Awardee_Investor'][0], $clientsByRole['EIC_Awardee'][0], $case);
            default:
                return FALSE;
        }

        //in any other case
        return FALSE;
    }

    /**
     * This method except an array of the following structure.
     *
     * @param array $clients
     * @return bool
     */
    private
    function assertClientRoles(array $clients): bool
    {
        //if more than 2 clients in case
        if (empty($clients) || sizeof($clients) != 2) {
            return FALSE;
        }
        $roles = [];

        return TRUE;
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
                $roleKey = implode("_", sort($client['contact_sub_type']));
                //this will create keys like "Investor", "EIC_Awardee", "EIC_Awardee_Investor", if more roles are added
                //this must be updated.
                $roles[$roleKey][] = $client['id'];
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
