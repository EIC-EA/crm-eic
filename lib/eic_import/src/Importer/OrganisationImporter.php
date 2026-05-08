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
    private const CONTACT_TYPE     = 'Organization';
    private const LOCATION_TYPE    = 'Main';   // CiviCRM location type for org addresses

    private array $fieldMap;

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        parent::__construct($config, $logger, $stats);
        $this->fieldMap = FieldMap::organisations();
    }

    // -------------------------------------------------------------------------
    // Entry point
    // -------------------------------------------------------------------------

    public function run(): void
    {
        $this->logger->section('Organisation Import');
        $this->logger->info('File      : ' . $this->config['file']);
        $this->logger->info('Delimiter : ' . $this->config['delimiter']);
        $this->logger->info('Dry run   : ' . ($this->isDryRun() ? 'YES' : 'NO'));
        $this->logger->info('On match  : ' . ($this->shouldUpdateExisting() ? 'Update' : 'Skip'));
        $this->logger->blank();

        $parser = new CsvParser(
            $this->config['file'],
            $this->config['delimiter'],
            $this->config['skip_rows']
        );

        $this->validateHeaders($parser->headers());

        foreach ($parser->rows() as $lineNumber => $row) {
            $this->stats->total++;
            $this->processRow($lineNumber, $row);
        }

        $this->stats->printSummary($this->logger);
    }

    // -------------------------------------------------------------------------
    // Per-row processing
    // -------------------------------------------------------------------------

    private function processRow(int $lineNumber, array $row): void
    {
        $pic = strtoupper(trim($row['PIC'] ?? ''));

        // 1. Validate
        $errors = $this->validate($row, $this->fieldMap);
        if (!empty($errors)) {
            $this->logger->error("Line {$lineNumber} | PIC {$pic} | Validation failed: " . implode('; ', $errors));
            $this->stats->recordError($lineNumber, $pic, implode('; ', $errors));
            return;
        }

        // 2. Transform values
        $row = $this->transform($row, $this->fieldMap);
        $pic = $row['PIC']; // re-read after transform

        // 3. Split into entity buckets
        $buckets = $this->bucketRow($row, $this->fieldMap);

        $this->logger->info(sprintf(
            'Line %-5d | PIC: %-15s | %s',
            $lineNumber,
            $pic,
            $buckets['contact']['organization_name'] ?? '(no name)'
        ));

        try {
            // 4. Deduplicate by PIC
            $existingContactId = $this->findByPic($pic);

            if ($existingContactId !== null) {
                if ($this->shouldUpdateExisting()) {
                    $this->updateOrganisation($existingContactId, $buckets);
                    $this->stats->updated++;
                    $this->logger->info("  → Updated (ID {$existingContactId})");
                } else {
                    $this->stats->skipped++;
                    $this->logger->info("  → Skipped (already exists, ID {$existingContactId})");
                }
                return;
            }

            // 5. Create new contact
            $contactId = $this->createOrganisation($buckets);
            $this->stats->created++;
            $this->logger->info("  → Created (ID {$contactId})");

        } catch (Throwable $e) {
            $this->stats->recordError($lineNumber, $pic, $e->getMessage());
            $this->logger->error("  → FAILED: " . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Deduplication
    // -------------------------------------------------------------------------

    /**
     * Look up an existing Organisation by PIC custom field value.
     * Returns the contact ID or null if not found.
     */
    private function findByPic(string $pic): ?int
    {
        if ($this->isDryRun() || $pic === '') {
            return null;
        }

        // Find the custom field that holds PIC — using dot-notation from the field map
        $picField = $this->fieldMap['PIC']['field']; // e.g. 'OrganisationDetails.pic'

        $result = \Civi\Api4\Contact::get()
            ->addSelect('id')
            ->addWhere('contact_type', '=', self::CONTACT_TYPE)
            ->addWhere($picField, '=', $pic)
            ->addWhere('is_deleted', '=', false)
            ->setLimit(1)
            ->execute();

        return $result->count() > 0 ? (int) $result->first()['id'] : null;
    }

    // -------------------------------------------------------------------------
    // Create
    // -------------------------------------------------------------------------

    private function createOrganisation(array $buckets): int
    {
        if ($this->isDryRun()) {
            return 0;
        }

        // --- Contact (with inline custom fields via dot-notation) ---
        $contactValues = array_merge(
            ['contact_type' => self::CONTACT_TYPE],
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

    private function updateOrganisation(int $contactId, array $buckets): void
    {
        if ($this->isDryRun()) {
            return;
        }

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

    // -------------------------------------------------------------------------
    // Sub-entity helpers
    // -------------------------------------------------------------------------

    private function createAddress(int $contactId, array $addressFields): void
    {
        $values = array_merge(
            $this->resolveAddressFields($addressFields),
            [
                'contact_id'             => $contactId,
                'location_type_id:name'  => self::LOCATION_TYPE,
                'is_primary'             => true,
            ]
        );

        \Civi\Api4\Address::create()
            ->setValues($values)
            ->execute();
    }

    /**
     * Resolve country name to country_id if needed.
     * APIv4 supports 'country_id:label' for direct name lookup,
     * but we also try numeric resolution as a fallback.
     */
    private function resolveAddressFields(array $addressFields): array
    {
        // If the field map uses 'country_id:label', APIv4 handles the lookup automatically.
        // We keep it as-is; no manual resolution needed.
        // If you prefer explicit IDs, swap to resolveCountryId() and use 'country_id' key.
        return $addressFields;
    }

    private function createWebsite(int $contactId, string $url): void
    {
        \Civi\Api4\Website::create()
            ->setValues([
                'contact_id'            => $contactId,
                'url'                   => $url,
                'website_type_id:name'  => 'Main',
            ])
            ->execute();
    }

    // -------------------------------------------------------------------------
    // Header validation
    // -------------------------------------------------------------------------

    private function validateHeaders(array $actualHeaders): void
    {
        $expectedHeaders = array_keys($this->fieldMap);
        $missing         = array_diff($expectedHeaders, $actualHeaders);
        $extra           = array_diff($actualHeaders, $expectedHeaders);

        if (!empty($missing)) {
            throw new RuntimeException(
                "CSV is missing expected columns: " . implode(', ', $missing)
            );
        }

        if (!empty($extra)) {
            $this->logger->warn("CSV contains unmapped columns (will be ignored): " . implode(', ', $extra));
        }

        $this->logger->info("Headers validated. Columns: " . implode(', ', $actualHeaders));
    }
}
