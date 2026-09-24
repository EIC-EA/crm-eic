<?php

use Civi\Api4\Generic\Result;


class CRM_CivirulesActions_Contact_SyncInvestorDomain extends CRM_Civirules_Action
{

    /**
     * @inheritdoc
     */
    public function getExtraDataInputUrl($ruleActionId)
    {
        return FALSE;
    }

    /**
     * @inheritdoc
     */
    public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData)
    {

        $contId = $triggerData->getContactId();
        // Find organization
        $entity = \Civi\Api4\Contact::get()
            ->addSelect('custom.*', 'website.*', 'id', 'contact_type')
            ->addJoin('Website AS website', 'LEFT', ['website.contact_id', '=', 'id'])
            ->addWhere('id', '=', $contId)
            ->execute()
            ->first();

        if ($entity['contact_type'] != 'Organization') {
            return;
        }
        $website = $entity['website.url'];
        $domainCurrent = $entity['EIC_Organisation_identifiers.Company_Domain_Name'];

        $domainFromWebsite = $this->stripDomain($website);
        //added validation for unique domains
        $isValidDomain = $this->isValidUniqueOrganizationDomain($contId, $domainFromWebsite);
        if ($domainFromWebsite != $domainCurrent && $isValidDomain) {
            try {
                $results = \Civi\Api4\Contact::update(TRUE);
                $results->addValue('EIC_Organisation_identifiers.Company_Domain_Name', $domainFromWebsite);
                $results->addWhere('contact_type', '=', 'Organization')
                    ->addWhere('contact_sub_type', 'IN', ['EIC_Awardee', 'Investor'])
                    ->addWhere('id', '=', $contId)
                    ->execute();
                \Civi::log('SyncInvestorDomain')->info("Sync for Investor or Awardee with id: $contId ");
            } catch (CRM_Core_Exception $e) {
                //do rollback?
                \Civi::log('SyncInvestorDomain')->error($e->getMessage());
            }
        } else {
            \Civi::log('SyncInvestorDomain')->info("Organization: $contId no automatic actions on domain.");
        }
    }


    /**
     * Extracts the domain from the full url via regex.
     *
     * @param string|null $website
     * @return array|string|string[]|null
     */
    public function stripDomain(?string $website): array|string|null
    {
        if (!$website) {
            return NULL;
        }
        $urlArray = parse_url($website);
        $domain = preg_replace('#^www\.(.+\.)#i', '$1', $urlArray['host']);
        if ($domain) {
            return $domain;
        }
        return NULL;
    }

    /**
     * Uses CiviCrm Api4 to query if the domain already exists in the database for an organization
     * other than the current one.
     * @param string|null $domain
     * @return array|string|null
     */
    private function isValidUniqueOrganizationDomain(int|string $orgId, ?string $domain): bool
    {
        if (!$domain) {
            return TRUE;
        }
        $contacts = \Civi\Api4\Contact::get(TRUE)
            ->addSelect('id', 'contact_type', 'contact_type:label', 'contact_sub_type', 'contact_sub_type:label', 'EIC_Organisation_identifiers.Company_Domain_Name')
            ->addWhere('contact_type', '=', 'Organization')
            ->addWhere('contact_sub_type', 'IN', ['Investor', 'EIC_Awardee'])
            ->addWhere('EIC_Organisation_identifiers.Company_Domain_Name', '=', $domain)
            ->setLimit(25)
            ->execute();

        foreach ($contacts as $contact) {
            if ($contact['id'] != $orgId) {
                $error = 'Could not automatically update Organization Domain. Duplicate Organization Domain: ' . $domain . ' belonging to Organization: ' . $contact['id'] . '.';
                \Civi::log('SyncInvestorDomain')->error($error);
                CRM_Core_Session::setStatus(
                    $error,
                    ts('Warning'),
                    'alert'
                );
                return FALSE;
            }
        }
        return TRUE;
    }

}
