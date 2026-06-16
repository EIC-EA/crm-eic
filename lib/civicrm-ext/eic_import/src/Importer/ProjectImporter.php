<?php
/**
 * src/Importer/ProjectImporter.php
 *
 * Imports Person contacts from a CSV file.
 *
 * CSV columns: program; cutOffDate; category; proposalNumber; current status; acronym; title; freeKeywords; fieldOfScience; fundingType; grantProposed; equityProposed; start date; end date; duration; Master; Primary; Subsector 1; Subsector 2; Tech cluster 1; Tech cluster 2; Tech cluster 3; Tech cluster 4; Tech cluster 5; Tech cluster 6; abstract
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

class ProjectImporter extends BaseImporter
{
    protected const ENTITY_TYPE     = 'Activity';
    protected const ENTITY_SUB_TYPE     = 'EIC_Awardee_Project';

    protected array $fieldMap;

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        parent::__construct($config, $logger, $stats);
        $this->PK = 'proposalNumber';
        $this->fieldMap = FieldMap::project();        
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
            ['activity_type_id:name' => static::ENTITY_SUB_TYPE],
            ['source_contact_id' => 2], // admin
            $buckets['activity'],
            $buckets['custom']
        );

        $result = \Civi\Api4\Activity::create(TRUE)
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

        // --- Update entity + custom fields ---
        $entityValues = array_merge(
            $buckets['activity'],
            $buckets['custom']
        );

        if (!empty($entityValues)) {
            \Civi\Api4\Activity::update()
                ->addWhere('id', '=', $entityIds['project_activity_id'])
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

        $result = \Civi\Api4\Activity::get()
            ->addSelect('id')
            ->addWhere('activity_type_id:name', '=', static::ENTITY_SUB_TYPE)
            ->addWhere($pkField, '=', $pkValue)
            ->addWhere('is_deleted', '=', false)
            ->setLimit(1)
            ->execute();

        return [ 'project_activity_id' => $result->count() > 0 ? (int) $result->first()['id'] : null ];
    }    
}