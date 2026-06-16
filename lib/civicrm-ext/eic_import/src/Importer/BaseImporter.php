<?php
/**
 * src/Importer/BaseImporter.php
 *
 * Abstract base class for all entity importers.
 *
 * Provides:
 *  - Shared config / logger / stats wiring
 *  - Row validation against a field map
 *  - Country name → CiviCRM country_id resolution (with cache)
 *  - Helper to split a mapped row into per-entity buckets
 *    (Contact fields, Address fields, custom fields, Website, etc.)
 */

declare(strict_types=1);

abstract class BaseImporter
{
    protected const LOCATION_TYPE    = 'Main';   // CiviCRM location type for org addresses
    protected static $CUSTOM_GROUP_EIC_AWARDEE_REPRESENTATIVE = 'EIC_Awardee_representative';
    protected static $CUSTOM_GROUP_HE_PROJECT_INFO = 'EIC_Horizon_Europe_Project_information';
    protected static $CUSTOM_GROUP_HE_RELATIONSHIP = 'EIC_Horizon_europe_Relationship';
    protected static $CUSTOM_GROUP_ORG_IDS = 'EIC_Organisation_identifiers'; 

    protected array        $config;
    protected ImportLogger $logger;
    protected ImportStats  $stats;
    protected string $PK;

    /** @var array<string, int> country name → country_id */
    protected array $countryCache = [];

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->stats  = $stats;
        $this->PK = 'base';    
    }

    // -------------------------------------------------------------------------
    // Contract
    // -------------------------------------------------------------------------

    //abstract public function run(): void;

    // -------------------------------------------------------------------------
    // Entry point
    // -------------------------------------------------------------------------

    public function run(): void
    {
        $this->logger->section(self::class.' Import');
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

    protected function processRow(int $lineNumber, array $row): void
    {
        $pkVal = strtoupper(trim($row[$this->PK] ?? ''));


        $row = $this->compute($row, $this->fieldMap);

        // 1. Validate
        $errors = $this->validate($row, $this->fieldMap);
        if (!empty($errors)) {
            $this->logger->error("Line {$lineNumber} | {$this->PK} {$pkVal} | Validation failed: " . implode('; ', $errors));
            $this->stats->recordError($lineNumber, $pkVal, implode('; ', $errors));
            return;
        }

        // 2. Transform values
        $row = $this->transform($row, $this->fieldMap);
        $pkVal = $row[$this->PK]; // re-read after transform

        // 3. Split into entity buckets
        $buckets = $this->bucketRow($row, $this->fieldMap);

        $this->logger->info(sprintf(
            "Line %-5d | {$this->PK} : %-15s",
            $lineNumber,
            $pkVal
        ));

        try {
            // 4. Deduplicate by PK
            $existingEntityIds = $this->findByPK($this->PK, $row);

            // The row cannot be processed
            if ($existingEntityIds === false)
                return;

            if (!in_array(null, $existingEntityIds, true)) {
                if ($this->shouldUpdateExisting()) {
                    $this->updateEntity($existingEntityIds, $buckets);
                    $this->stats->updated++;
                    $this->logger->info("  → Updated (ID {".implode(',', $existingEntityIds)."})");
                } else {
                    $this->stats->skipped++;
                    $this->logger->info("  → Skipped (already exists, ID {".implode(',', $existingEntityIds)."})");
                }
                return;
            }

            // 5. Create new contact
            $entityId = $this->createEntity($buckets);
            $this->stats->created++;
            $this->logger->info("  → Created (ID {$entityId})");

        } catch (Throwable $e) {
            $this->stats->recordError($lineNumber, $pkVal, $e->getMessage());
            $this->logger->error("  → FAILED: " . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Shared helpers
    // -------------------------------------------------------------------------

    /**
     * Validate a CSV row against the field map.
     * Returns a list of validation error strings (empty = valid).
     */
    protected function validate(array $row, array $fieldMap): array
    {
        $errors = [];

        foreach ($fieldMap as $csvHeader => $def) {
            if (array_key_exists('computed', $def)) {
                $row[$csvHeader] = '_';
            }

            $value = $row[$csvHeader] ?? '';

            if (!empty($def['required']) && trim($value) === '') {
                $errors[] = "Required field '{$csvHeader}' is empty.";
            }
        }

        return $errors;
    }

    /**
     * Apply transform callables from the field map to a row.
     */
    protected function transform(array $row, array $fieldMap): array
    {
        foreach ($fieldMap as $csvHeader => $def) {
            if (!array_key_exists('computed', $def) &&  // do not transform computed fields
                array_key_exists($csvHeader, $row) &&
                !empty($def['transform']) &&
                is_callable($def['transform'])) 
            {
                $row[$csvHeader] = ($def['transform'])((string) $row[$csvHeader], $row);
            }
        }
        return $row;
    }

    /**
     * Apply transform callables from the field map to a row.
     */
    protected function compute(array $row, array $fieldMap): array
    {
        foreach ($fieldMap as $csvHeader => $def) {
            if (array_key_exists('computed', $def) && !empty($def['transform']) && is_callable($def['transform'])) {
                $row[$csvHeader] = ($def['transform'])('', $row);
            }
        }
        return $row;
    }

    /**
     * Split a row into per-entity buckets ready for APIv4 calls.
     *
     * Returns:
     * [
     *   'contact'  => ['organization_name' => '...', ...],
     *   'address'  => ['street_address' => '...', 'city' => '...', ...],
     *   'website'  => ['url' => '...'],
     *   'custom'   => ['GroupName.field' => '...', ...],
     *   'dedupe_key' => ['GroupName.pic' => '...'],   // fields marked unique:true
     * ]
     */
    protected function bucketRow(array $row, array $fieldMap): array
    {
        $buckets = [
            'contact'    => [],
            'activity'    => [],
            'address'    => [],
            'website'    => [],
            'email'      => [],
            'phone'      => [],
            'custom'     => [],
            'dedupe_key' => [],
            'option_value' => [],
        ];

        foreach ($fieldMap as $csvHeader => $def) {
            $value = $row[$csvHeader] ?? '';

            if ($value === '') {
                continue; // Don't send empty values to the API
            }

            $entity = $def['entity'];
            $field  = $def['field'];

            switch ($entity) {
                case 'Contact':
                    $buckets['contact'][$field] = $value;
                    break;
                case 'Activity':
                    $buckets['activity'][$field] = $value;
                    break;                    
                case 'Address':
                    $buckets['address'][$field] = $value;
                    break;
                case 'Website':
                    $buckets['website'][$field] = $value;
                    break;
                case 'Email':
                    $buckets['email'][$field] = $value;
                    break;
                case 'Phone':
                    $buckets['phone'][$field] = $value;
                    break;
                case 'OptionValue':
                    $buckets['option_value'][$field] = $value;
                    break;                    
                case 'custom':
                    $buckets['custom'][$field] = $value;
                    // Also track dedupe keys
                    if (!empty($def['unique'])) {
                        $buckets['dedupe_key'][$field] = $value;
                    }
                    break;
            }
        }

        return $buckets;
    }

    // -------------------------------------------------------------------------
    // Country resolution
    // -------------------------------------------------------------------------

    /**
     * Resolve a country name string to a CiviCRM country_id.
     * Results are cached for the lifetime of the import run.
     */
    protected function resolveCountryId(string $countryName): ?int
    {
        if ($countryName === '') {
            return null;
        }

        if ($countryName == 'UK')
            $countryName = 'GB';
        if ($countryName == 'EL')
            $countryName = 'GR';

        if (array_key_exists($countryName, $this->countryCache)) {
            return $this->countryCache[$countryName];
        }

        $result = \Civi\Api4\Country::get()
            ->addSelect('id')
            ->addWhere('iso_code', '=', $countryName)
            ->setLimit(1)
            ->execute();

        $id = $result->count() > 0 ? (int) $result->first()['id'] : null;

        if ($id === null) {
            $this->logger->warn("Country not found in CiviCRM: '{$countryName}'");
        }

        $this->countryCache[$countryName] = $id;
        return $id;
    }


    // -------------------------------------------------------------------------
    // Deduplication
    // -------------------------------------------------------------------------

    /**
     * Look up an existing Organisation by PK custom field value.
     * Returns the contact ID or null if not found.
     */
    protected function findByPK(string $pkName, array $row): array
    {
        $pkValue = $row[$pkName];

        if ($this->isDryRun() || $pkValue === '') {
            return null;
        }

        // Find the custom field that holds PIC — using dot-notation from the field map
        $pkField = $this->fieldMap[$pkName]['field']; // e.g. 'OrganisationDetails.pic'

        $result = \Civi\Api4\Contact::get()
            ->addSelect('id')
            ->addWhere('contact_type', '=', static::CONTACT_TYPE)
            ->addWhere($pkField, '=', $pkValue)
            ->addWhere('is_deleted', '=', false)
            ->setLimit(1)
            ->execute();

        return ['id' => $result->count() > 0 ? (int) $result->first()['id'] : null];
    }

    // -------------------------------------------------------------------------
    // Dry-run guard
    // -------------------------------------------------------------------------

    protected function isDryRun(): bool
    {
        return (bool) ($this->config['dry_run'] ?? false);
    }

    protected function shouldUpdateExisting(): bool
    {
        return (bool) ($this->config['update_existing'] ?? false);
    }

    // -------------------------------------------------------------------------
    // Sub-entity helpers
    // -------------------------------------------------------------------------

    protected function createAddress(int $contactId, array $addressFields): void
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
    protected function resolveAddressFields(array $addressFields): array
    {
        // If the field map uses 'country_id:label', APIv4 handles the lookup automatically.
        // We keep it as-is; no manual resolution needed.
        // If you prefer explicit IDs, swap to resolveCountryId() and use 'country_id' key.
        $addressFields['country_id'] = $this->resolveCountryId($addressFields['country_id']);

        return $addressFields;
    }

    protected function createWebsite(int $contactId, string $url): void
    {
        \Civi\Api4\Website::create()
            ->setValues([
                'contact_id'            => $contactId,
                'url'                   => $url,
                'website_type_id:name'  => 'Main',
            ])
            ->execute();
    }

    protected function createEmail(int $contactId, string $email): void
    {
        \Civi\Api4\Email::create()
            ->setValues([
                'contact_id'            => $contactId,
                'email'                   => $email,
                'location_type_id:name'  => 'Main',
            ])
            ->execute();
    }    

    // -------------------------------------------------------------------------
    // Header validation
    // -------------------------------------------------------------------------

    protected function validateHeaders(array $actualHeaders): void
    {
        $expectedHeaders = array_keys(
            array_filter(
                $this->fieldMap,
                function($v, $k) { 
                    if (array_key_exists('computed', $v)) return false; 
                    if (array_key_exists("required", $v) && $v["required"]) return true;
                },
                ARRAY_FILTER_USE_BOTH
            )
        );

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
