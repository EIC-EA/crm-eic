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
    protected array        $config;
    protected ImportLogger $logger;
    protected ImportStats  $stats;

    /** @var array<string, int> country name → country_id */
    private array $countryCache = [];

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->stats  = $stats;
    }

    // -------------------------------------------------------------------------
    // Contract
    // -------------------------------------------------------------------------

    abstract public function run(): void;

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
            if (!array_key_exists($csvHeader, $row)) {
                continue;
            }
            if (!empty($def['transform']) && is_callable($def['transform'])) {
                $row[$csvHeader] = ($def['transform'])((string) $row[$csvHeader]);
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
            'address'    => [],
            'website'    => [],
            'email'      => [],
            'phone'      => [],
            'custom'     => [],
            'dedupe_key' => [],
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

        if (array_key_exists($countryName, $this->countryCache)) {
            return $this->countryCache[$countryName];
        }

        $result = \Civi\Api4\Country::get()
            ->addSelect('id')
            ->addWhere('name', '=', $countryName)
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
}
