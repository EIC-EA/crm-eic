# EIC SRM — Configuration register (single source of truth)

This folder is the **centralized data-management register** for the EIC SRM CiviCRM platform. It documents, in a form the team can maintain and discuss with the business, every configurable object defined across the repository's extensions (`eic_config`, `eic_eu_survey_form_processor`, `nc_config`).

Open the CSVs as a workbook (each file = one tab). They are plain CSV so they are editable in Excel and diff-friendly in git.

## Files (tabs)

| File | Contents |
|---|---|
| `01_contact_types.csv` | Contact types and sub-types (EIC Awardee, EIC Organisation, Investor, representatives) |
| `02_relationship_types.csv` | Relationship types (F&TP roles, BAS contacts, KAM, Investor) with A/B directions and endpoints |
| `03_activity_types.csv` | Activity types (EIC Awardee Project, EU-Survey data, Task) |
| `04_case_types.csv` | Case types incl. Onboarding, VentureMatch, and the 10 Service Request types, with workflow/timeline notes |
| `05_tags.csv` | BAS programme parent tags (contractors add their own child tags) |
| `06_custom_fields.csv` | Every custom field, its group, the entity/sub-type it extends, type, options, source, current + proposed help_post |
| `07_case_statuses.csv` | Custom case statuses (Onboarded, Declined) |

## How to maintain

- **One change, one update.** When config changes in a `.mgd.php` file, update the matching row here in the same change.
- **Proposed vs current.** `Current ...` columns record what is in the platform today; `Proposed ...` columns hold text to apply. Once applied to the managed file, copy Proposed into Current.
- **Security/ACL column** is intentionally reserved (empty) for the upcoming access-control work — do not invent values yet.
- **Owner / Status / Last reviewed** support governance: who owns the object, whether it is Active/Deprecated/Shipped-not-triggered, and when it was last checked.

## Scope note

Rows are sourced from the managed entity definitions in the repository. The `06_custom_fields.csv` survey-question group (`eic_accelerator_onboarding_survey_data`) is represented as a single group-level row; the full per-question list lives in `eic_eu_survey_form_processor/README.md` and can be expanded here on request.
