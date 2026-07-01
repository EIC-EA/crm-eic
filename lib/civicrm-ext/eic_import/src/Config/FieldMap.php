<?php
/**
 * src/Config/FieldMap.php
 *
 * Defines how each CSV column maps to a CiviCRM APIv4 field.
 *
 * Structure per entry:
 *   'CSV_HEADER' => [
 *       'entity'   => 'Contact' | 'Address' | 'Website' | 'Phone' | 'Email'
 *                     or a custom group name like 'MyGroup'
 *       'field'    => APIv4 field name (for Contact/Address/etc.)
 *                     or 'GroupName.field_name' for custom fields
 *       'required' => true|false   — validation fails if empty
 *       'transform'=> callable     — optional value transformer
 *   ]
 *
 * Custom fields use dot-notation: 'CustomGroupName.custom_field_name'
 * These names must match exactly what is defined in CiviCRM
 * (Admin > Custom Data — the "Name" column, not the label).
 *
 * EDIT THIS FILE to match your CiviCRM custom group and field names.
 */

declare(strict_types=1);

class FieldMap
{

    protected const CUSTOM_GROUP_EIC_AWARDEE_REPRESENTATIVE = 'EIC_Awardee_representative'; //
    protected const CUSTOM_GROUP_HE_PROJECT_INFO = 'EIC_Horizon_Europe_Project_information'; //
    protected const CUSTOM_GROUP_HE_RELATIONSHIP = 'EIC_Horizon_europe_Relationship'; //
    protected const CUSTOM_GROUP_ORG_IDS = 'EIC_Organisation_identifiers'; //
    protected const CUSTOM_GROUP_AWARDEE = 'EIC_Awardee_Additionnal_Information'; //

    /**
     * Field map for the organisations CSV.
     *
     * CSV headers: PIC	 SMEDashboardId	 legalName	 streetNameAndNumber	 postalCode	 city	 country	 website	 program	 higher or secondary education establishment	 research organisation	 large research infrastructure	 non-profit organisation	 public body	 international organisation	 european interest	 legal personality	 legal status	 SME vs mid-cap status	 SME status type
     *
     * Assumptions:
     *  - "PIC" is stored in a custom field. Custom group: OrganisationDetails, field: pic
     *  - "program" is stored in a custom field. Custom group: OrganisationDetails, field: program
     *  - All other fields map to standard CiviCRM Contact / Address / Website entities.
     *
     * Adjust 'OrganisationDetails.pic' and 'OrganisationDetails.program' below
     * to match your actual custom group and field names in CiviCRM.
     */
    public static function organisations(): array
    {
        return [

            // ------------------------------------------------------------------
            // PIC — custom field (unique project identifier / participant ID)
            // Used as the deduplication key: if a contact with this PIC already
            // exists it will be updated or skipped depending on --update-existing.
            // ------------------------------------------------------------------
            'PIC' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_ORG_IDS.'.PIC',
                'required' => true,
                'unique'   => true,    // used as dedupe key
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],

            'SMEDashboardId' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_ORG_IDS.'.SMEDId',
                'required' => true,
                'unique'   => false,    // used as dedupe key
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],

            // ------------------------------------------------------------------
            // Core contact fields
            // ------------------------------------------------------------------
            'legalName' => [
                'entity'   => 'Contact',
                'field'    => 'organization_name',
                'required' => true,
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'generated_legalName' => [
                'entity'   => 'Contact',
                'field'    => 'legal_name',                
                'computed' => true,
                'transform' => fn(string $v, $r) => trim($r['legalName']),
            ],            

            // ------------------------------------------------------------------
            // Address fields — all grouped into one Address::create() call
            // ------------------------------------------------------------------
            'streetNameAndNumber' => [
                'entity'   => 'Address',
                'field'    => 'street_address',                
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'postalCode' => [
                'entity'   => 'Address',
                'field'    => 'postal_code',            
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'city' => [
                'entity'   => 'Address',
                'field'    => 'city',                
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'country' => [
                'entity'   => 'Address',
                'field'    => 'country_id',  // APIv4 accepts country name via :label suffix                
                'transform' => fn(string $v, $r) => trim($v),
            ],

            // ------------------------------------------------------------------
            // Website — stored as a Website entity
            // ------------------------------------------------------------------
            'website' => [
                'entity'   => 'Website',
                'field'    => 'url',
                
                'transform' => function (string $v, $r): string {
                    $v = trim($v);
                    if ($v && !preg_match('#^https?://#i', $v)) {
                        $v = 'https://' . $v;
                    }
                    return $v;
                },
            ],

            // ------------------------------------------------------------------
            // Program — custom field
            // ------------------------------------------------------------------
            'program' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Program',                
                'transform' => fn(string $v, $r) => trim($v),
            ],	 	 	 	 	 	 	 
            'higher or secondary education establishment' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Higher_or_secondary_education_establishment',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'research organisation' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Research_organisation',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'large research infrastructure' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Large_research_infrastructure',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'non-profit organisation' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Non_profit_organisation',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'public body' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Public_body',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'international organisation' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.International_organisation',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'european interest' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.European_interest',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'legal personality' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Legal_personality',                
                'transform' => function (string $v, $r) : ?bool { if ($v == '1') return (FALSE); if ($v == '2') return (TRUE);  return(null); },
            ],
            'legal status' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.Legal_status',                
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'SME vs mid-cap status' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.SME_vs_mid_cap_status',                
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'SME status type' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_AWARDEE.'.SME_status_type',                
                'transform' => fn(string $v, $r) => trim($v),
            ]
        ];
    }

    /**
     * Field map for the person CSV.
     *
     * CSV headers: source; eu-login; sex; firstName; lastName; e-mail; function
     *
     */
    public static function person(): array
    {
        return [

            'generated_ext_id' => [
                'entity'   => 'Contact',
                'field'    => 'external_identifier',
                'required' => true,
                'unique'   => true,    // used as dedupe key
                'computed' => true,
                'transform' => fn(string $v, $r) => trim($r['e-mail']),
            ],
        
            // ------------------------------------------------------------------
            // eulogin — custom field 
            // Used as the deduplication key: if a contact with this eulogin already
            // exists it will be updated or skipped depending on --update-existing.
            // ------------------------------------------------------------------
            'eu-login' => [
                'entity'   => 'custom',
                'field'    => self::CUSTOM_GROUP_EIC_AWARDEE_REPRESENTATIVE.'.eulogin',
                'unique'   => false,    // used as dedupe key
                'transform' => fn(string $v, $r) => strtolower(trim($v)),
            ],

            // ------------------------------------------------------------------
            // Core contact fields
            // ------------------------------------------------------------------
            'firstName' => [
                'entity'   => 'Contact',
                'field'    => 'first_name',
                'required' => true,
                'transform' => fn(string $v, $r) => trim($v),
            ],

            'lastName' => [
                'entity'   => 'Contact',
                'field'    => 'last_name',
                'required' => true,
                'transform' => fn(string $v, $r) => trim($v),
            ],

            'sex' => [
                'entity'   => 'Contact',
                'field'    => 'gender_id',
                'transform' => function (string $v, $r): int {
                    $v = trim($v);
                    switch ($v) {
                        case 'F':
                            return 1;
                            break;
                        case 'M':
                            return 2;
                            break;                        
                    }
                    return 0;
                },
            ],

            'function' => [
                'entity'   => 'Contact',
                'field'    => 'job_title',
                'transform' => fn(string $v, $r) => trim($v),
            ],

            'e-mail' => [
                'entity'   => 'Email',
                'field'    => 'email',
                'transform' => fn(string $v, $r) => trim($v),
            ],

        ];
    }

    /**
     * Field map for the project CSV.
     *
     * CSV headers: program; cutOffDate; category; proposalNumber; current status; acronym; title; freeKeywords; fieldOfScience; fundingType; grantProposed; equityProposed; start date; end date; duration; Master; Primary; Subsector 1; Subsector 2; Tech cluster 1; Tech cluster 2; Tech cluster 3; Tech cluster 4; Tech cluster 5; Tech cluster 6; abstract
     *
     */
    public static function project(): array
    {
        $CustomFieldGroup = self::CUSTOM_GROUP_HE_PROJECT_INFO;

        return [

            // ------------------------------------------------------------------
            // proposalNumber — custom field 
            // Used as the deduplication key: if a entity with this field value already
            // exists it will be updated or skipped depending on --update-existing.
            // ------------------------------------------------------------------
            'proposalNumber' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Project_Number',
                'required' => true,
                'unique'   => true,    // used as dedupe key
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'program' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Funding',
                'transform' => function(string $v, $r): ?string {
                    if ($v == 'SME Instrument / EIC Pilot') {
                        if (in_array($r['cutOffDate'], [
                            '2019-10-09',
                            '2020-01-08',
                            '2020-03-20',
                            '2020-05-19',
                            '2020-10-07',                                                                                                                
                        ]))
                            return 'EIC Pilot';
                        else
                            return 'SME Instrument';
                    }
                    else
                        return trim($v);
                },
            ],
            'phase' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Phase',
                'transform' => function(string $v, $r): ?string {
                    $phase = explode('/', $v);
                    return trim($phase[0]);
                },
            ],            
            'cutOffDate' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Cut_Off_Date',
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'category' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Category',
                'transform' => function(string $v, $r): ?string {
                    switch ($v) {
                        case 'project':
                            if ($r['program'] == 'SME Instrument' ||  $r['program'] == 'EIC Pilot') {
                                return 'H2020';
                            }
                            else
                                return 'heproject';
                            break;
                        case 'soe':
                            return 'hesoe';
                            break;                        
                        default:
                            return null;
                            break;
                    }
                },
            ],
            'title' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Project_Title',
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'freeKeywords' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Free_Keywords',
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'fieldOfScience' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Field_Of_Science',
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'fundingType' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Funding_Type',
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'grantProposed' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Grant_Proposed',
                'transform' => function(string $v, $r) : string {
                    $val = trim($v);
                    if (is_numeric($val))
                        return $val;
                    return '0';               
                }
            ],
            'equityProposed' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Equity_Proposed',
                'transform' => function(string $v, $r) : string {
                    $val = trim($v);
                    if (is_numeric($val))
                        return $val;
                    return '0';               
                }                
            ],
            'start date' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Start_Date',
                'transform' => fn(string $v, $r) => trim($v)
            ],            
            'end date' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.End_Date',
                'transform' => fn(string $v, $r) => trim($v)
            ],
            'duration' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Duration',
                'transform' => fn(string $v, $r) => trim($v)
            ],
            'Master' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Master',
                'transform' => fn(string $v, $r) => trim($v)
            ],
            'Primary' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Primary',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Subsector 1' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Subsector_1',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Subsector 2' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Subsector_2',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Tech cluster 1' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Tech_cluster_1',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Tech cluster 2' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Tech_cluster_2',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Tech cluster 3' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Tech_cluster_3',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Tech cluster 4' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Tech_cluster_4',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Tech cluster 5' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Tech_cluster_5',
                'transform' => fn(string $v, $r) => trim($v)
            ],                                    
            'Tech cluster 6' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Tech_cluster_6',
                'transform' => fn(string $v, $r) => trim($v)
            ],
            'current status' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.EC_tool_status',
                'transform' => fn(string $v, $r) => trim($v)
            ],
            'termination' => [
                'entity'   => 'custom',
                'field'    => $CustomFieldGroup.'.Termination',
                'transform' => fn(string $v, $r) => trim($v)
            ],                        
            // ------------------------------------------------------------------
            // Core entity fields
            // ------------------------------------------------------------------
            'acronym' => [
                'entity'   => 'Activity',
                'field'    => 'subject',
                'required' => true,
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'abstract' => [
                'entity'   => 'Activity',
                'field'    => 'details',
                'transform' => fn(string $v, $r) => base64_decode($v),                
            ],
           'status' => [
                'entity'   => 'Activity',
                'field'    => 'status_id:name',
                'unique'   => false,    // used as dedupe key
                'computed' => true,
                'transform' => function (string $v, $r) : string { 
                    if (array_key_exists('termination', $r) && ($r['termination'] != ''))
                        return 'Cancelled';
                    if (array_key_exists('current status', $r) && ($r['current status'] == 'Completed'))
                        return 'Completed';
                    return 'Ongoing';               
                },
            ],
            'cutOffDateComputed' => [
                'entity'   => 'custom',
                'computed' => true,                
                'field'    => 'activity_date_time',
                'transform' => fn(string $v, $r) => trim($r['cutOffDate']),
            ],
        ];
    }

    /**
     * Field map for the contact CSV.
     *
     * CSV headers: source; eu-login; role; PIC; proposalNo
     *
     */
    public static function contact(): array
    {
        $CustomFieldGroup = self::CUSTOM_GROUP_HE_RELATIONSHIP;

        return [

            // ------------------------------------------------------------------
            // proposalNumber — custom field 
            // Used as the deduplication key: if a entity with this field value already
            // exists it will be updated or skipped depending on --update-existing.
            // ------------------------------------------------------------------
            'eu-login' => [
                'entity'   => 'custom',
                'field'    => '',
                'unique'   => true,    // used as dedupe key
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'e-mail' => [
                'entity'   => 'custom',
                'field'    => '',
                'unique'   => true,    // used as dedupe key
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],            
            'role' => [
                'entity'   => 'custom',
                'field'    => '',
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'PIC' => [
                'entity'   => 'custom',
                'required' => true,
                'field'    => '',
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'proposalNo' => [
                'entity'   => 'custom',
                'required' => true,
                'field'    => '',
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'status' => [
                'entity'   => 'custom',
                'field'    => 'status',
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
        ];
    }    

    /**
     * Field map for the contact CSV.
     *
     * CSV headers: proposalNumber; relation; status; SMEDashboardId; PIC; Name; Country
     *
     */
    public static function consortium(): array
    {
        return [

            // ------------------------------------------------------------------
            // proposalNumber — custom field 
            // Used as the deduplication key: if a entity with this field value already
            // exists it will be updated or skipped depending on --update-existing.
            // ------------------------------------------------------------------
            'proposalNumber' => [
                'entity'   => 'custom',
                'field'    => '',
                'required' => true,
                'unique'   => true,    // used as dedupe key
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'PIC' => [
                'entity'   => 'custom',
                'required' => true,
                'field'    => '',
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'relation' => [
                'entity'   => 'custom',
                'required' => true,
                'field'    => '',
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'status' => [
                'entity'   => 'custom',
                'field'    => '',
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],                                 
        ];
    }

    public static function sector(): array
    {
        // "Code","Label","Description"
        return [
            'Code' => [
                'entity'   => 'OptionValue',
                'field'    => 'value',
                'required' => true,
                'unique'   => true,    // used as dedupe key
                'transform' => fn(string $v, $r) => strtoupper(trim($v)),
            ],
            'Label' => [
                'entity'   => 'OptionValue',
                'field'    => 'label',
                'required' => true,
                'unique'   => false,    // used as dedupe key
                'transform' => fn(string $v, $r) => trim($v),
            ],
            'Description' => [
                'entity'   => 'OptionValue',
                'field'    => 'description',
                'unique'   => false,    // used as dedupe key
                'transform' => fn(string $v, $r) => trim($v),
            ],
            
        ];
    }
}
