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

class SectorImporter extends BaseImporter
{
    protected const OPTION_GROUP_NAME     = 'eic_primary_sector';
    protected array $fieldMap;

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        parent::__construct($config, $logger, $stats);
        $this->PK = 'Code';
        $this->fieldMap = FieldMap::sector();      
    }

    // -------------------------------------------------------------------------
    // Create
    // -------------------------------------------------------------------------

    protected function createEntity(array $buckets): int
    {
        if ($this->isDryRun()) {
            return 0;
        }

        $entityValues = array_merge(
            ['option_group_id.name' => self::OPTION_GROUP_NAME],
            $buckets['option_value']
        );

        $result = \Civi\Api4\OptionValue::create()
            ->setValues($entityValues)
            ->execute();
        $entityId = (int) $result->first()['id'];

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

        $entityValues = array_merge(
            ['option_group_id.name' => self::OPTION_GROUP_NAME],
            $buckets['option_value']
        );

        if (!empty($entityValues)) {
            \Civi\Api4\OptionValue::update()
                ->addWhere('id', '=', $entityId)
                ->setValues($entityValues)
                ->execute();
        }
    }

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

        $pkField = $this->fieldMap[$pkName]['field'];

        $result = \Civi\Api4\OptionValue::get()
            ->addSelect('id')
            ->addWhere('option_group_id.name', '=', self::OPTION_GROUP_NAME)
            ->addWhere($pkField, '=', $pkValue)
            ->setLimit(1)
            ->execute();

        return [ 'id' => $result->count() > 0 ? (int) $result->first()['id'] : null ];
    }      
}
