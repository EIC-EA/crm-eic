<?php
/**
 * src/Importer/PersonImporter.php
 *
 * Imports Person contacts from a CSV file.
 *
 * CSV columns: source; eu-login; sex; firstName; lastName; e-mail;function
 *
 * Per row this importer will:
 *  1. Validate required fields
 *  2. Deduplicate by PIC (custom field) — skip or update depending on flag
 *  3. Create / update the Contact (Indivudual)
 *  4. Create / update the primary Address
 *  5. Create / update the Website
 *  6. Write custom fields (PIC, program) using dot-notation
 */

declare(strict_types=1);

class PersonImporter extends BaseImporter
{
    protected const CONTACT_TYPE     = 'Individual';
    protected const CONTACT_SUB_TYPE     = 'EIC_Registered';
    protected const LOCATION_TYPE    = 'Main';   // CiviCRM location type for org addresses

    protected array $fieldMap;

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        parent::__construct($config, $logger, $stats);
        $this->PK = 'generated_ext_id';
        $this->fieldMap = FieldMap::person();      
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
        $entityValues = array_merge(
            ['contact_type' => self::CONTACT_TYPE],
            ['contact_sub_type' => self::CONTACT_SUB_TYPE],
            $buckets['contact'],
            $buckets['custom']   // e.g. OrganisationDetails.pic, OrganisationDetails.program
        );

        $result = \Civi\Api4\Contact::create()
            ->setValues($entityValues)
            ->execute();
        $entityId = (int) $result->first()['id'];

        // --- Email ---
        if (!empty($buckets['email']['email'])) {
            $this->createEmail($entityId, $buckets['email']['email']);
        }

        return $entityId;
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    protected function updateEntity(array $entityIds, array $buckets): void
    {
        if ($this->isDryRun()) {
            return;
        }
        
        $entityId = $entityIds['id'];

        // --- Update Contact + custom fields ---
        $entityValues = array_merge(
            ['contact_sub_type' => self::CONTACT_SUB_TYPE],
            $buckets['contact'],
            $buckets['custom']
        );

        if (!empty($entityValues)) {
            \Civi\Api4\Contact::update()
                ->addWhere('id', '=', $entityId)
                ->setValues($entityValues)
                ->execute();
        }

        // --- Update or create Email ---
        if (!empty($buckets['email']['email'])) {
            $existingEmail = \Civi\Api4\Email::get()
                ->addSelect('id')
                ->addWhere('contact_id', '=', $entityId)
                ->setLimit(1)
                ->execute();

            if ($existingEmail->count() > 0) {
                $emailId = (int) $existingEmail->first()['id'];
                \Civi\Api4\Email::update()
                    ->addWhere('id', '=', $emailId)
                    ->setValues(['email' => $buckets['email']['email']])
                    ->execute();
            } else {
                $this->createEmail($entityId, $buckets['email']['email']);
            }
        }
    }
}
