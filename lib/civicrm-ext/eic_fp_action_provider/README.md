# eic_fp_action_provider
Provides reusable CiviCRM Form-Processor action-provider conditions and actions
for the EIC extensions. It currently ships:

- **`CompareParameters`** (condition) - compares two run-time parameters against
  each other with a configurable operator (`>`, `>=`, `<`, `<=`, `=`, `!=`).
- **`GetCaseTypeIdByName`** (action) - resolves a CiviCRM `CaseType` id from its
  machine name (falling back to its title). Input parameter `name`, output
  parameter `id`. This lets Form Processors reference case types by name instead
  of hardcoding the numeric id, which is not portable across environments (the
  id is assigned by the database on install). It mirrors the built-in
  `GetRelationshipTypeIdByName` action.
- **`GetActivityIdByCustomField`** (action) - resolves a single Activity id by
  matching a value against a named custom field, optionally restricted to an
  activity type. Config `custom_field` (API name `GroupName.FieldName`, e.g.
  `EIC_Horizon_Europe_Project_information.Project_Number`) and optional
  `activity_type` (machine name, e.g. `EIC_Awardee_Project`); input `value`;
  output `activity_id` (most recent match). This is the Activity equivalent of
  the built-in `FindContactByCustomField`: it references the field and type by
  name (no numeric ids) and returns a single id (unlike `FindSimilarActivities`,
  which returns an array), so it can feed `GetActivity` or a single-value
  EntityReference field.
- **`ActivityUpdateCustomData`** (action) - updates ONLY the custom field values
  of an existing Activity, identified by `activity_id`. It is the Activity
  equivalent of the built-in `CaseUpdateCustomData`. Use it to patch custom data
  on an existing activity without touching anything else: the built-in
  `CreateActivity` cannot do this because it treats `source_contact_id` /
  `target_contact_id` as required (blanking them on update) and only accepts a
  single fixed status from config, so it fails ("Could not create activity") or
  clobbers the activity's source/target/status. Input `activity_id` plus one
  `custom_<group>_<field>` per active Activity custom field.

This extension is deliberately separate from the form-processor consumers (such
as `eic_eu_survey_form_processor`) so that the condition and action are
registered by an extension that is enabled *before* the container is rebuilt
during install. That ordering is what lets a consumer import form processors
that reference them during its own enable, without hitting a stale-container
`null`.

This is an [extension for CiviCRM](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/), licensed under [AGPL-3.0](LICENSE.txt).

## Getting Started

(* FIXME: Where would a new user navigate to get started? What changes would they see? *)

## Known Issues

(* FIXME *)
