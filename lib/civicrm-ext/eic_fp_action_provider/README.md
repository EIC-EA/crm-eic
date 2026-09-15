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
