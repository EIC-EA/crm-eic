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
    /**
     * Field map for the organisations CSV.
     *
     * CSV headers: PIC; legalName; streetNameAndNumber; postalCode; city; country; website; program
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
                'field'    => 'EIC_Awardee_Organization.PIC',
                'required' => true,
                'unique'   => true,    // used as dedupe key
                'transform' => fn(string $v) => strtoupper(trim($v)),
            ],

            // ------------------------------------------------------------------
            // Core contact fields
            // ------------------------------------------------------------------
            'legalName' => [
                'entity'   => 'Contact',
                'field'    => 'organization_name',
                'required' => true,
                'transform' => fn(string $v) => trim($v),
            ],

            // ------------------------------------------------------------------
            // Address fields — all grouped into one Address::create() call
            // ------------------------------------------------------------------
            'streetNameAndNumber' => [
                'entity'   => 'Address',
                'field'    => 'street_address',
                'required' => false,
                'transform' => fn(string $v) => trim($v),
            ],
            'postalCode' => [
                'entity'   => 'Address',
                'field'    => 'postal_code',
                'required' => false,
                'transform' => fn(string $v) => trim($v),
            ],
            'city' => [
                'entity'   => 'Address',
                'field'    => 'city',
                'required' => false,
                'transform' => fn(string $v) => trim($v),
            ],
            'country' => [
                'entity'   => 'Address',
                'field'    => 'country_id:label',  // APIv4 accepts country name via :label suffix
                'required' => false,
                'transform' => fn(string $v) => trim($v),
            ],

            // ------------------------------------------------------------------
            // Website — stored as a Website entity
            // ------------------------------------------------------------------
            'website' => [
                'entity'   => 'Website',
                'field'    => 'url',
                'required' => false,
                'transform' => function (string $v): string {
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
                'field'    => 'EIC_Awardee_Organization.PIC',  // <-- adjust to your group.field
                'required' => false,
                'transform' => fn(string $v) => trim($v),
            ],
        ];
    }
}
