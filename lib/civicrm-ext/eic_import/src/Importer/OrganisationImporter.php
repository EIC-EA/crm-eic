<?php
/**
 * src/Importer/OrganisationImporter.php
 *
 * Imports Organisation contacts from a CSV file.
 *
 * CSV columns: PIC; legalName; streetNameAndNumber; postalCode; city; country; website; program
 *
 * Per row this importer will:
 *  1. Validate required fields
 *  2. Deduplicate by PIC (custom field) — skip or update depending on flag
 *  3. Create / update the Contact (Organization)
 *  4. Create / update the primary Address
 *  5. Create / update the Website
 *  6. Write custom fields (PIC, program) using dot-notation
 */

declare(strict_types=1);

class OrganisationImporter extends BaseImporter
{
    protected const CONTACT_TYPE     = 'Organization';
    protected const CONTACT_SUB_TYPE     = 'EIC_Awardee';    

    protected array $fieldMap;

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        parent::__construct($config, $logger, $stats);
        $this->PK = 'legalName';
        $this->fieldMap = FieldMap::organisations();
    }

    // -------------------------------------------------------------------------
    // Create
    // -------------------------------------------------------------------------

    protected function createEntity(array $buckets): int
    {
        if ($this->isDryRun()) {
            return 0;
        }

        // --- Contact (with inline custom fields via dot-notation) ---
        $contactValues = array_merge(
            ['contact_type' => self::CONTACT_TYPE],
            ['contact_sub_type' => self::CONTACT_SUB_TYPE],
            $buckets['contact'],
            $buckets['custom']   // e.g. OrganisationDetails.pic, OrganisationDetails.program
        );

        $result    = \Civi\Api4\Contact::create()            
            ->setValues($contactValues)
            ->execute();
        $contactId = (int) $result->first()['id'];

        // --- Address ---
        if (!empty($buckets['address'])) {
            $this->createAddress($contactId, $buckets['address']);
        }

        // --- Website ---
        if (!empty($buckets['website']['url'])) {
            $this->createWebsite($contactId, $buckets['website']['url']);
        }

        return $contactId;
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    protected function updateEntity(array $entityIds, array $buckets): void
    {
        if ($this->isDryRun()) {
            return;
        }

        $contactId = $entityIds['id'];

        // --- Update Contact + custom fields ---
        $contactValues = array_merge(
            $buckets['contact'],
            $buckets['custom']
        );

        if (!empty($contactValues)) {
            \Civi\Api4\Contact::update()
                ->addWhere('id', '=', $contactId)
                ->setValues($contactValues)
                ->execute();
        }

        // --- Update or create Address ---
        if (!empty($buckets['address'])) {
            $existingAddress = \Civi\Api4\Address::get()
                ->addSelect('id')
                ->addWhere('contact_id', '=', $contactId)
                ->addWhere('is_primary', '=', true)
                ->setLimit(1)
                ->execute();

            if ($existingAddress->count() > 0) {
                $addressId = (int) $existingAddress->first()['id'];
                \Civi\Api4\Address::update()
                    ->addWhere('id', '=', $addressId)
                    ->setValues($this->resolveAddressFields($buckets['address']))
                    ->execute();
            } else {
                $this->createAddress($contactId, $buckets['address']);
            }
        }

        // --- Update or create Website ---
        if (!empty($buckets['website']['url'])) {
            $existingWebsite = \Civi\Api4\Website::get()
                ->addSelect('id')
                ->addWhere('contact_id', '=', $contactId)
                ->setLimit(1)
                ->execute();

            if ($existingWebsite->count() > 0) {
                $websiteId = (int) $existingWebsite->first()['id'];
                \Civi\Api4\Website::update()
                    ->addWhere('id', '=', $websiteId)
                    ->setValues(['url' => $buckets['website']['url']])
                    ->execute();
            } else {
                $this->createWebsite($contactId, $buckets['website']['url']);
            }
        }
    }
}
