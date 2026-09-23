# New Custom Field Request & Catalog

This document has two purposes:

1. **Template** — the format every new custom field must be described in *before* it is created in the platform. Users typically arrive from an Excel column, so the template mirrors what an Excel row contains and adds the CRM-specific facts we need.
2. **Living catalog** — the list of custom fields that already exist, filled in using the same template. This doubles as documentation for both new users and for an AI connected to the platform.

The documentation captured here should be mirrored into each field's **Field Post Help** (`help_post`) so it is visible in the CiviCRM UI and readable through the API4 `CustomField` metadata. One source, two consumers (humans + AI).

---

## How a field gets created

```
Excel column  ->  fill the request template below  ->  reviewer approves  ->  .mgd.php entry written in eic_config/managed  ->  help_post populated
```

A field request is **not complete** (and the field will not be created) until every required column is filled, including **Post Help** and the **Calculated** section when the field is derived.

---

## Request template (columns)

Everything is one flat table: one row per field, with the custom group and the calculated logic as columns (no separate sections). Add a new field by appending a row to the catalog below using this exact column order.

| Column | Required | Meaning |
|---|---|---|
| **Custom group** | Yes | The block/group the field belongs to. |
| **Extends (entity)** | Yes | The CiviCRM entity the field is attached to: Contact (sub-type), Activity (type), Relationship (type), Case, etc. |
| **Label** | Yes | Human-facing name shown in the UI. |
| **Machine name** | Yes | `name` / `column_name` in the managed entity. No spaces. |
| **Data type** | Yes | Text, Int, Money, Date, Boolean, Memo, ContactReference, EntityReference, or a Select backed by an option list. |
| **Options** | If Select | The allowed values (or the option group name if it already exists). |
| **Source** | Yes | Where the value comes from: `Manual`, `Import`, `EC corporate tools`, `Form processor`, or **`Calculated`**. |
| **Calculated?** | Yes | `No`, `Yes`, or `Review`. If Yes/Review, fill the next four columns. |
| **Depends on** | If Calculated | The source fields used (label + machine name). |
| **Rule** | If Calculated | The derivation logic, plainly enough to implement and verify. |
| **Trigger** | If Calculated | When it recomputes: on create, on source edit, on import, scheduled, on demand. |
| **On missing input** | If Calculated | Value when a source field is empty (e.g. leave empty, 0, N/A). |
| **Read-only** | Yes | Calculated fields should be `Yes` so users don't overwrite them; direct fields `No`. |
| **Post Help (help_post)** | Yes | The documentation string. See structure below. |

### Post Help structure

Keep it short but include the machine-useful facts, in this order:

```
Definition: <one plain-language sentence>.
Source: <Manual | Import | EC corporate tools | Form processor | Calculated>.
Format: <expected format or example>.
Used for: <what it drives: matching, reporting, dedupe, eligibility...>.
```

---

## Calculated (derived) fields

A field is **Calculated** when its value is not entered by a user but computed from other fields already in the application. When a user maps an Excel column that is really a derivation, set `Source = Calculated` and fill the `Depends on` / `Rule` / `Trigger` / `On missing input` / `Read-only` columns on the same row — no separate section is needed.

Guidance for those columns:

- **Depends on** — the exact source fields used (label + machine name).
- **Rule** — the logic, written plainly enough to implement and to verify (formula, condition, lookup, or concatenation).
- **Trigger** — on create, on edit of a source field, on import, scheduled, or on demand.
- **On missing input** — the value when a source field is empty (e.g. leave empty, 0, N/A).
- **Read-only** — should be `Yes` so users don't overwrite the computed value.

> Note on implementation: CiviCRM has no native spreadsheet-style formula on custom fields. Calculated fields are realised with the tools already in this platform — the **form processor** (`eic_eu_survey_form_processor`), the **action provider** (`eic_fp_action_provider`), scheduled jobs, or `apiv4` post-hooks. The row only needs to describe the *rule*; the reviewer decides the mechanism.

**Example row (Duration = End − Start):** see the `Duration (Month)` row in the catalog below, where `Source = Calculated`, `Depends on = Start Date; End Date`, `Rule = whole months between Start and End`, `Read-only = Yes`.

---

## Current field catalog

One row per field. The custom group and the entity it extends are **columns**, not section headers, so the whole catalog is a single flat table (easy to paste into / export from Excel and to feed an AI).

`Source` reflects the current intent; set it to **Calculated** if the value is in fact derived and fill the `Depends on` / `Rule` / `Trigger` columns. Fields marked *(needs Post Help)* do not yet have `help_post` populated and should be backfilled.

Column order matches the request template so a new row can be added directly:

| Custom group | Extends (entity) | Label | Machine name | Data type | Options | Source | Calculated? | Depends on | Rule | Trigger | On missing input | Read-only | Post Help status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Organisation identifiers | Organization (EIC_Awardee, Investor, EIC_Organisation) | SMEDId | smed_id | Text (255) |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| Organisation identifiers | Organization (EIC_Awardee, Investor, EIC_Organisation) | PIC | pic | Text (255) |  | EC corporate tools | No |  |  |  |  | No | Has help_pre; mirror to Post Help |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Program | program | Select | EIC Accelerator, ... | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Research organisation | research_organisation | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Large research infrastructure | large_research_infrastructure | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Non-profit organisation | non_profit_org | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Public body | public_body | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | International organisation | intl_org | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | European interest | eu_interest | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Legal status | legal_status | Select | LEGAL_PERSON, ... | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | SME vs mid-cap status | sme_mid_cap | Select | Not an SME, SME, Mid-cap, N/A | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | SME status type | sme_status_type | Select | Self-assessment, Validation services, N/A | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Higher or secondary education establishment | edu_est | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| EIC Awardee Additionnal Information | Organization (EIC_Awardee) | Legal personality | legal_personality | Boolean |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* — data_type/html_type mismatch to review |
| EIC Awardee representative | Individual (EIC_Registered) | eulogin | eulogin (column: funds_vintage_year) | Text (255) |  | Manual | No |  |  |  |  | No | *(needs Post Help)* — column_name mismatch to review |
| Horizon europe Relationship | Relationship (EIC Coco / Project member / LEAR / PaCo / PCoco) | Horizon Europe Project | he_project | EntityReference -> Activity |  | Manual / Form processor | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Project Title | project_title | Text (255) |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Project Number | project_number | Text (255), searchable |  | Import / EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | EC tool status | ec_tool_status | Text (255) |  | EC corporate tools | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Coordinator | coordinator | ContactReference (EIC_Awardee), required |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Partners | partners | ContactReference (EIC_Awardee), multi |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Cut-Off-Date | cut_off_date | Date, required, searchable |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Category | category | Select | HE Project, HE SoE, H2020 | Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Funding | funding | Select | EIC programme list | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Funding Type | funding_type | Select | Blended, Grant first, Grant only, Investment only | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Grant Proposed | grant_proposed | Money |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Equity Proposed | equity_proposed | Money |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Start Date | start_date | Date |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | End Date | end_date | Date |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Duration (Month) | duration_months | Int |  | Calculated (candidate) | Review | Start Date (start_date); End Date (end_date) | Whole months between Start and End (End minus Start, rounded) | On create + on edit of Start/End | Leave empty if either date missing | Yes | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Master | master_sector | Select | Green, Digital, Health, Social | Calculated (candidate) | Review | Primary (primary_sector) | Map from Primary sector code prefix (e.g. G.->Green, D.->Digital, H.->Health) | On create + on edit of Primary | Leave empty if Primary missing | Yes | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Primary | primary_sector | Select | eic_primary_sector | Import / Manual | No |  |  |  |  | No | Option values have descriptions |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Subsector 1 | subsector_1 | Select | eic_primary_sector | Import / Manual | No |  |  |  |  | No | Option values have descriptions |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Subsector 2 | subsector_2 | Select | eic_primary_sector | Import / Manual | No |  |  |  |  | No | Option values have descriptions |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Tech cluster 1..6 | tech_cluster_1..6 | Select | eic_primary_sector | Import / Manual | No |  |  |  |  | No | Option values have descriptions |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Field Of Science | field_of_science | Text (512) |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Free Keywords | free_keywords | Memo |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Termination | termination | Select | GAP termination, EU request, Beneficiary request | Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Associated Partners | associated_partners | ContactReference (EIC_Awardee), multi |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Terminated Partners | terminated_partners | ContactReference (EIC_Awardee), multi |  | Import / Manual | No |  |  |  |  | No | *(needs Post Help)* |
| Horizon Europe Project Activity | Activity (EIC_Awardee_Project) | Phase | phase | Select | Phase 1, Phase 2 | Manual | No |  |  |  |  | No | *(needs Post Help)* |
| EIC VentureMatch Service Request | Case (eic_sr_venturematch) | What fundraising support do you need most? | fundraising_support_needed | Text (255) |  | Form processor | No |  |  |  |  | Yes (view) | *(needs Post Help)* |
| EIC VentureMatch Service Request | Case (eic_sr_venturematch) | Fundraising within | fundraising_within | Text (255) |  | Form processor | No |  |  |  |  | Yes (view) | Has help_post |
| EIC Innovation Procurement Service Request | Case (eic_sr_innovation_procurement) | Are you planning to sell your innovative solution to public or private buyers, e.g. through tender opportunities? | selling_to_public_private_buyers | Text (255) |  | Form processor | No |  |  |  |  | Yes (view) | Has help_post |
| EIC Innovation Procurement Service Request | Case (eic_sr_innovation_procurement) | Where are you in the process? | where_are_you_in_the_process | Text (255) |  | Form processor | No |  |  |  |  | Yes (view) | Has help_post |
| EIC Coaching Service Request | Case (eic_sr_coaching) | Support required: | support_required | Text (255) |  | Form processor | No |  |  |  |  | Yes (view) | Has help_post |
| EIC Coaching Service Request | Case (eic_sr_coaching) | Main challenges | main_challenge | Text (255) |  | Form processor | No |  |  |  |  | Yes (view) | Has help_post |

---

## Excel import mapping cheat-sheet

When onboarding a user's Excel file, map each column to one row of the request template. For every column decide:

1. Is this a **direct value** (Manual / Import) or is it **Calculated** from other columns/fields already in the platform?
2. If Calculated, do not create an editable field — capture the **Depends on / Rule / Trigger** and mark it read-only.
3. Write the **Post Help** now, not later. A column without Post Help is not ready to become a field.
