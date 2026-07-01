<?php
/**
 * src/Importer/ProjectImporter.php
 *
 * Imports Person contacts from a CSV file.
 *
 * CSV columns: source, eu-login, role, PIC, proposalNo
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

class ContactImporter extends BaseImporter
{
    protected const ENTITY_TYPE     = 'Activity';
    protected const ENTITY_SUB_TYPE     = 'EIC_Awardee_Project';

    protected array $fieldMap;

    protected int $contact_id_a;
    protected int $contact_id_b;
    protected ?int $project_activity_id;
    protected ?int $relationship_id;
    protected ?int $activity_contact_id;
    protected string $relationship_type_id_name;

    public function __construct(array $config, ImportLogger $logger, ImportStats $stats)
    {
        parent::__construct($config, $logger, $stats);
        $this->PK = 'eu-login';
        $this->contact_id_a = -1;
        $this->fieldMap = FieldMap::contact();        
    }

    // -------------------------------------------------------------------------
    // Create
    // -------------------------------------------------------------------------

    protected function createEntity(array $buckets): int
    {
        if ($this->isDryRun()) {
            return 0;
        }

        if (!$this->relationship_id) {            
            $entityValues = array_merge(
                ['relationship_type_id:name' => $this->relationship_type_id_name],
                ['contact_id_a' => $this->contact_id_a],
                ['contact_id_b' => $this->contact_id_b],
                ['is_active' => $buckets['custom']['status'] != 'REVOKED']
            );
            
            if ($this->project_activity_id)
                $entityValues = array_merge(
                    $entityValues,
                    [self::$CUSTOM_GROUP_HE_RELATIONSHIP.'.Horizon_Europe_Project' => $this->project_activity_id],
            );        

            $result = \Civi\Api4\Relationship::create(TRUE)
                ->setValues($entityValues)
                ->execute();
            $this->relationship_id = (int) $result->first()['id'];
        }

        if (!$this->activity_contact_id) {
            if ($this->project_activity_id) {
                $result = \Civi\Api4\ActivityContact::create(TRUE)
                    ->addValue('activity_id', $this->project_activity_id)
                    ->addValue('contact_id',  $this->contact_id_a)
                    ->addValue('record_type_id', 3) // Activity Targets
                    ->execute();
                $this->activity_contact_id = (int) $result->first()['id'];
            }
        }
        
        return $this->relationship_id;
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
        // eu-login; role; PIC; proposalNo

        if ($this->isDryRun()) {
            return null;
        }

        $result = \Civi\Api4\Relationship::get()
            ->addSelect('id')
            ->addWhere('relationship_type_id:name', '=', $this->relationship_type_id_name)
            ->addWhere('contact_id_a', '=', $this->contact_id_a)
            ->addWhere('contact_id_b', '=', $this->contact_id_b)
            ->addWhere(self::$CUSTOM_GROUP_HE_RELATIONSHIP.'.Horizon_Europe_Project', '=', $this->project_activity_id)
            ->setLimit(1)
            ->execute();

        $this->relationship_id = $result->count() > 0 ? (int) $result->first()['id'] : null;

        $result = \Civi\Api4\ActivityContact::get()
            ->addSelect('id')
            ->addWhere('record_type_id', '=', 3)
            ->addWhere('activity_id', '=', $this->project_activity_id)
            ->addWhere('contact_id', '=', $this->contact_id_a)
            ->setLimit(1)
            ->execute();        

        $this->activity_contact_id =  $result->count() > 0 ? (int) $result->first()['id'] : null;

        return [
            'relationship_id' => $this->relationship_id,
            'activity_contact_id' => $this->activity_contact_id
        ];
    }
    
    protected function getRelationshipTypeNameFromRole(string $role) : string
    {
        $relationship_type_id_name = 'EIC Project member of';
        switch ($role)
        {
            case 'COORDINATOR':
              $relationship_type_id_name = 'EIC_PCoco_For';
              break;
            case 'LEAR':
              $relationship_type_id_name = 'EIC_LEAR_For';
              break;
            case 'PARTICIPANT_CONTACT':
              $relationship_type_id_name = 'EIC_PaCo_For';
              break;
            case 'COORDINATOR_CONTACT':
              $relationship_type_id_name = 'EIC_Coco_For';
              break;              
        }
        return $relationship_type_id_name;
    }

    protected function validate(array $row, array $fieldMap): array
    {
        $fieldMapCheck = [];

        $fieldMapCheck = parent::validate($row, $fieldMap);
        if (!empty($fieldMapCheck))
            return $fieldMapCheck;


        // If the related role does not exist, we have an issue
        $this->relationship_type_id_name = $this->getRelationshipTypeNameFromRole($row['role']);
        if ($this->relationship_type_id_name == '')
        {
            $fieldMapCheck[] = "Role not found ".$row['role'];
            return $fieldMapCheck;
        }

        // If the related project does not exist, we have an issue
        if ($row['role'] == 'LEAR') // LEAR does not need a project
            $this->project_activity_id = null;
        else
        {
            if (!is_numeric($row['proposalNo']))
            {            
                $fieldMapCheck[] = "No project number ".$row['proposalNo'];
                return $fieldMapCheck;
            }
        
        // Get project activity
            $result = \Civi\Api4\Activity::get()
                ->addSelect('id')
                ->addWhere('activity_type_id:name', '=', static::ENTITY_SUB_TYPE)
                ->addWhere(self::$CUSTOM_GROUP_HE_PROJECT_INFO.'.Project_Number', '=',  $row['proposalNo'])
                ->addWhere('is_deleted', '=', false)
                ->setLimit(1)
                ->execute();
            
            // If the related project does not exist, we have an issue
            if ($result->count() == 0)
            {
                $fieldMapCheck[] = "Project not found ".$row['proposalNo'];
                return $fieldMapCheck;
            }
            $this->project_activity_id =  (int) $result->first()['id'];
        }
            

        // Get the individual
        if (array_key_exists('eu-login', $row) && $row['eu-login'] != '') {
            $result = \Civi\Api4\Contact::get()
                ->addSelect('id')
                ->addWhere('contact_type', '=', 'Individual')
                ->addWhere(self::$CUSTOM_GROUP_EIC_AWARDEE_REPRESENTATIVE.'.eulogin', '=', $row['eu-login'])
                ->addWhere('is_deleted', '=', false)
                ->setLimit(1)
                ->execute();
            if ($result->count() != 0)
                $this->contact_id_a =  (int) $result->first()['id'];
        }

        var_dump($row);

        if ( $this->contact_id_a == -1 && ( !array_key_exists('e-mail', $row) || $row['e-mail'] == '' ) ) {
                $fieldMapCheck[] = "Individual not found by eu-login and e-mail is empty ";
                return $fieldMapCheck;
        }
        else
        {
            $result = \Civi\Api4\Contact::get()
                ->addSelect('id')
                ->addWhere('contact_type', '=', 'Individual')
                ->addWhere('email_primary.email', '=', $row['e-mail'])
                ->addWhere('is_deleted', '=', false)
                ->setLimit(1)
                ->execute();
            // If the related organisation does not exist, we have an issue
            if ($result->count() == 0)
            {
                $fieldMapCheck[] = "Individual not found ".$row['e-mail'];
                return $fieldMapCheck;
            }
            else
                $this->contact_id_a =  (int) $result->first()['id'];
        }

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

        $this->contact_id_b =  (int) $result->first()['id'];

        return $fieldMapCheck;
    }
}