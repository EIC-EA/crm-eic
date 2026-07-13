<?php
/**
 * src/Importer/ProjectImporter.php
 *
 * Imports Person contacts from a CSV file.
 *
 * CSV columns: proposalNumber, relation, status, SMEDashboardId, PIC, Name, Country
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

class ConsortiumImporter extends BaseImporter
{
    protected const ENTITY_TYPE     = 'Activity';
    protected const ENTITY_SUB_TYPE     = 'EIC_Awardee_Project';

    protected array $fieldMap;

    protected int $organization_id;
    protected int $project_activity_id;
    protected string $field_name;
    protected array $field_value;
    protected ?int $filled_project_activity_id;
    protected ?int $activity_contact_id;

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        parent::__construct($config, $logger, $stats);
        $this->PK = 'proposalNumber';
        $this->fieldMap = FieldMap::consortium();        
    }

    // -------------------------------------------------------------------------
    // Create
    // -------------------------------------------------------------------------

    protected function createEntity(array $buckets): int
    {
        if ($this->isDryRun()) {
            return 0;
        }

        if (!$this->filled_project_activity_id) { // The project does not already have the field value
            if ($this->field_name == self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Coordinator')
            {
                $value = $this->organization_id; // There is only a single coordinator
            }
            else
            {
                $value = [$this->organization_id];
                if (is_array($this->field_value))
                    $value = array_merge($value, $this->field_value);
            }
                

            $result = \Civi\Api4\Activity::update()
                ->addWhere('id', '=', $this->project_activity_id)
                ->addValue($this->field_name, $value)
                ->execute();
        }

        if (!$this->activity_contact_id) {
            $results = \Civi\Api4\ActivityContact::create(TRUE)
                ->addValue('activity_id', $this->project_activity_id)
                ->addValue('contact_id', $this->organization_id)
                ->addValue('record_type_id', 3) // Activity Targets
                ->execute();
        }

        return $this->project_activity_id;
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    protected function updateEntity(array $entityIds, array $buckets): void
    {
        $this->createEntity($buckets);
    }

    /**
     * Look up an existing Organisation by PK custom field value.
     * Returns the contact ID or null if not found.
     */
    protected function findByPK(string $pkName, array $row): array | bool
    {
        // proposalNumber	 relation	 status	 SMEDashboardId	 PIC	 Name	 Country

        if ($this->isDryRun()) {
            return null;
        }

        $ids = [];

        $result = \Civi\Api4\Activity::get()
            ->addSelect('id')
            ->addSelect($this->field_name)
            ->addWhere('activity_type_id:name', '=', static::ENTITY_SUB_TYPE)
            ->addWhere(self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Project_Number', '=',  $row['proposalNumber'])
            ->addWhere($this->field_name, 'CONTAINS', $this->organization_id)
            ->addWhere('is_deleted', '=', false)
            ->setLimit(1)
            ->execute();
        
        $this->filled_project_activity_id  = $result->count() > 0 ? (int) $result->first()['id'] : null;

        $result = \Civi\Api4\ActivityContact::get()
            ->addSelect('id')
            ->addWhere('record_type_id', '=', 3)
            ->addWhere('activity_id', '=', $this->project_activity_id)
            ->addWhere('contact_id', '=', $this->organization_id)
            ->setLimit(1)
            ->execute();        

        $this->activity_contact_id =  $result->count() > 0 ? (int) $result->first()['id'] : null;        

        return [
            'filled_project_activity_id' => $this->filled_project_activity_id,
            'activity_contact_id' => $this->activity_contact_id
        ];
    }
    
    protected function getFieldNameFromRole(string $role, string $status, string $project_category) : string
    {
        $field_name = '';
        if ($status == 'TERMINATED' || $status == 'NOT_ACCEDED')
            return self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Terminated_Partners';
        if ($status == '')
            return self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Coordinator';
        switch ($role)
        {
            case 'Partner':
              $field_name = self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Partners';
              break;
            case 'Coordinator':
              $field_name = self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Coordinator';
              break;
            case 'AssociatedPartner':
              $field_name = self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Associated_Partners';
              break;
        }
        return $field_name;
    }

    protected function validate(array $row, array $fieldMap): array
    {
        $fieldMapCheck = [];
        $this->organization_id = 0;
        $this->project_activity_id = 0;
        $this->field_name = '';
        $this->field_value = [];
        $this->filled_project_activity_id = null;
        $this->activity_contact_id = null;        

        $fieldMapCheck = parent::validate($row, $fieldMap);
        if (!empty($fieldMapCheck))
            return $fieldMapCheck;

        // If the related project does not exist, we have an issue
        if (!is_numeric($row['proposalNumber']))
        {
            $fieldMapCheck[] = "No project number ".$row['proposalNumber'];
            return $fieldMapCheck;
        }
        // Get project activity
        $result = \Civi\Api4\Activity::get()
            ->addSelect('id')
            ->addSelect('custom.*')
            ->addWhere('activity_type_id:name', '=', static::ENTITY_SUB_TYPE)
            ->addWhere(self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Project_Number', '=',  $row['proposalNumber'])
            ->addWhere('is_deleted', '=', false)
            ->setLimit(1)
            ->execute();
        
        // If the related project does not exist, we have an issue
        if ($result->count() == 0)
        {
            $fieldMapCheck[] = "Project not found ".$row['proposalNumber'];
            return $fieldMapCheck;
        }

        $this->project_activity_id = (int) $result->first()['id'];
        $project_category = $result->first()[self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Category'];

        // If the related role does not exist, we have an issue
        $this->field_name = $this->getFieldNameFromRole($row['relation'], $row['status'], $project_category);

        if ($this->field_name == '')
        {
            $fieldMapCheck[] = "Role not found ".$row['relation'];
            return $fieldMapCheck;
        }

        $this->field_value = is_array($result->first()[$this->field_name]) ? $result->first()[$this->field_name] : [$result->first()[$this->field_name]];        
        
        // $row['PIC']
        $result = \Civi\Api4\Contact::get()
            ->addSelect('id')
            ->addWhere('contact_type', '=', 'Organization')
            ->addWhere(self::$CUSTOM_GROUP_ORG_IDS.'.PIC', '=', $row['PIC'])
            ->addWhere('is_deleted', '=', false)
            ->setLimit(1)
            ->execute();
        
        // If the related organisation does not exist, we have an issue
        if ($result->count() == 0)
        {
            $fieldMapCheck[] = "Organization not found ".$row['PIC'];
            return $fieldMapCheck;
        }

        $this->organization_id = (int) $result->first()['id'];

        return $fieldMapCheck;
    }
}