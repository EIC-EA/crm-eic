# Spec — EIC VM Fundraising Assessment EU-Survey Import

> Status: **Implemented** (see §11a for implementation notes/limitations).
> Extension: `eic_eu_survey_form_processor`
> Form Processor name: `eic_vm_fundraising_assessment_eu_survey_import`

## 1. Overview

A new scheme-specific Form Processor that ingests the **EIC Awardee Fundraising
Assessment** EU-Survey and **creates or updates a VentureMatch Service Request
case** (`eic_sr_venturematch`).

Unlike the Accelerator onboarding survey, this form does **not** carry a PIC
number, so the company is matched on other keys. The bulk of the survey answers
are company-level attributes and are stored on the **Organisation**, not on the
case.

## 2. Company resolution (no PIC)

- Resolution is done through a **dedicated XCM profile** (new):
  `EU_Survey_VM_Fundraising_Company`.
- Match keys (configured in the profile): **Company LinkedIn URL**, **Company
  Name**, **Company Website URL**.
- Company Website URL is written to the Organisation `website`.
- If no company matches, the XCM profile **creates an EIC Organisation**
  (`contact_type: EIC_Organisation`). This matches the existing convention: the
  Company Import (`eic_company_import`) already creates companies with
  `contact_type: EIC_Organisation` (via XCM profile `eu_survey_company`), so the
  subtype already exists and is reused here.

## 3. EIC Project resolution

- Match the EIC Project activity by **EIC project number** (form field #26) or
  **EIC project acronym** (#25), using the same technique as the Accelerator
  import: `GetActivityIdByCustomField` on
  `EIC_Horizon_Europe_Project_information.Project_Number`, with acronym/subject
  as the fallback match.
- A project match may be absent; the flow must not fail when no project is found.

## 4. Case find-or-create (VentureMatch SR)

- Find an existing `eic_sr_venturematch` case for the resolved company.
  **Project-based uniqueness is NOT enforced for now** — one company is expected
  to have at most one VentureMatch SR case at this stage.
- **If a case is found:** update it. The shared **EIC Awardee information** group
  is already populated on that case → leave it unchanged.
- **If no case is found:** create the VentureMatch SR case (status `Requested`)
  and **populate what we can** into the shared **EIC Awardee information** group,
  including the project-derived values when a project matched: **EIC Project ID,
  EIC Project Acronym, Category, Funding, Funding Type, Cut-Off-Date** (partial if
  no project match).

## 5. Case roles (VentureMatch SR)

**Case manager.** The `eic_sr_venturematch` case type keeps the generic
`Case Coordinator` as its manager role (unchanged). We do **not** restrict the
case role to any access-control group (e.g. an `EIC_VentureMatch` group): CiviCRM
has no per-role group restriction in the managed CaseType definition, and we are
intentionally not adding a custom form hook for it now. Role assignment relies on
the existing ACL visibility.

**Founder role.** New **RelationshipType** (managed, `update => always`):
- `name_a_b` = **"Founder of"** (Individual → Organisation)
- `name_b_a` = **"Founder is"**
- Added to the `eic_sr_venturematch` case type `caseRoles`.
- The **Founder's Primary Contact Email** contact (#2) is **found-or-created via
  XCM, matched on email** (reuse `eu_survey_individual` — *assumption, confirm*):
  - the survey email is set as the contact's **main email**;
  - the survey phone (#3) is set as the contact's **main phone**;
  - the contact is linked to the case in the **Founder** role.

## 6. Activity

- Create a **Task** activity (existing activity type), subject **"EU Survey
  received"**, status **Completed**, assigned to the case.
- **No custom fields** on this activity — all survey data is stored on the
  Organisation (and the shared case group).

## 7. New custom group — "EIC Fundraising Assessment" (Organisation-level)

- **Extends:** `Organization`
- **Subtypes:** `EIC_Awardee`, `EIC_Organisation`
- Each field's help text/description notes that it comes from the Fundraising
  Assessment EU-Survey.
- All new managed entities use `update => always`.

| # | Label | Type | Notes |
|---|-------|------|-------|
| 6  | Primary Sector/Industry Cluster | Select | New option group `eic_vm_sector_cluster` (10 values). Distinct from the EU-Survey onboarding "Sector". |
| 7  | Current Fundraising Round | Select | New option group `eic_vm_fundraising_round`: Pre-Seed, Seed, Series-A, Series-B, Series-C, Series-D+ |
| 8  | Amount Currently Raising | Money | EUR |
| 9  | Current Runway (months) | Integer | |
| 10 | Have you raised capital before? | Yes/No | |
| 11 | List Current Key Investors | Text (long) | Free text; investors separated by ";" |
| 12 | Total Amount Raised to Date | Money | EUR |
| 13 | Target Close Date for Current Round | Date | |
| 14 | Intended Use of Funds (current round) | Text | |
| 16 | Link to Pitchdeck | Text (URL, clickable) | |
| 17 | Existing Metrics / Financial Model Link | Text (URL, clickable) | |
| 18 | Existing Metrics / Data Room Link | Text (URL, clickable) | |
| 20 | Priorities (select 1–2) | Multi-select | New option group `eic_vm_priorities` (9 values) |
| 22 | Dealroom Profile Link / Dealroom Company ID | Text | |
| 27 | Country of Incorporation / Primary Operation | Text | |

**Not imported / removed from scope:**
- #19 Date — being removed from the form; not imported.
- #21 Links accessible confirmation — not imported.
- #24 Free Text Question — dropped (unclear purpose).

## 8. Other field placement

- **Company LinkedIn Profile URL (#5)** → new **clickable** (URL) field on the
  existing **`EIC_Organisation_identifiers`** group (Organisation level). Kept as
  a potential future company-matching key.
- **Pitch deck Upload (#15)** → **not managed** (skipped for now).
- **EIC programmes funded under (#23, multi-select)** → **not imported** — the
  platform already holds this. Pending customer confirmation to remove it from the
  survey.

## 9. Field inventory (from the form)

Company / contact block:
1. Company Name *(required)* — company match / create
2. Founder's Primary Contact Email *(required)* — Founder role contact (main email)
3. Founder's primary contact phone number — Founder contact main phone
4. Company Website URL *(required)* — Organisation website + company match
5. Company LinkedIn Profile URL — `EIC_Organisation_identifiers` (clickable)
6. Primary Sector/Industry Cluster *(required)* — Fundraising Assessment group
7. Current Fundraising Round *(required)* — Fundraising Assessment group
8. Amount Currently Raising — Fundraising Assessment group
9. Current Runway (months) — Fundraising Assessment group

Fundraising Snapshot block:
10. Have you raised capital before? — Fundraising Assessment group
11. List Current Key Investors — Fundraising Assessment group
12. Total Amount Raised to Date — Fundraising Assessment group
13. Target Close Date for Current Round — Fundraising Assessment group
14. Intended Use of Funds — Fundraising Assessment group
15. Pitch deck Upload (PDF) — **not managed**
16. Link to Pitchdeck — Fundraising Assessment group
17. Existing Metrics / Financial Model Link — Fundraising Assessment group
18. Existing Metrics / Data Room Link — Fundraising Assessment group
19. Date — **not imported** (being removed from the form)

Priorities block:
20. Priorities (Multiple Choice, select 1–2) — Fundraising Assessment group
21. Links accessible confirmation — **not imported**
22. Dealroom Profile Link / Dealroom Company ID — Fundraising Assessment group
23. EIC programmes funded under — **not imported**
24. Free Text Question — **not imported** (dropped)
25. EIC project acronym — project match
26. EIC project number — project match
27. Country of Incorporation / Primary Operation — Fundraising Assessment group

## 10. Managed-entity policy

All new managed entities use `update => always` for cross-environment
determinism. New files land in `eic_eu_survey_form_processor/managed/`:
- CustomGroup "EIC Fundraising Assessment" + its CustomFields
- Option groups `eic_vm_sector_cluster`, `eic_vm_priorities` (+ their values)
- CustomField LinkedIn URL on `EIC_Organisation_identifiers`
- RelationshipType "Founder of" / "Founder is"
- Update to `CaseType_eic_sr_venturematch` (add Founder case role)
- XCM profiles: `EU_Survey_VM_Fundraising_Company` (+ reuse `eu_survey_individual`)

## 11. Documentation

After implementation, update:
- `eic_eu_survey_form_processor/README.md` (new FP, new group, new role)
- `docs/06_custom_fields.csv`, `docs/02_relationship_types.csv`, FP list

## 11a. Implementation notes / deviations

- **Fundraising Round option group:** implemented as its own option group
  `eic_vm_fundraising_round` (Pre-Seed, Seed, Series A/B/C) rather than inline
  values, for consistency with the other selects.
- **Effective case id (found-or-created):** the FormProcessor has no coalesce
  action, so the found case id and the newly-created case id (mutually exclusive)
  are merged with a `Concatenate` action (empty separator); the resulting
  `.concatenation` output is the effective case id used for the Founder role and
  the Task activity.
- **Project match — acronym fallback NOT yet implemented.** The FP currently
  matches the EIC Project by **project number** only
  (`find_eic_project_activity_by_project_number`). The spec allows number OR
  acronym; the acronym fallback is a TODO (would need a second
  `GetActivityIdByCustomField`/subject match + a coalesce).
- **XCM profile `fill_fields`:** XCM persists company custom fields only when
  their **numeric `custom_NN` ids** are listed in the profile's `fill_fields`
  (see `EU_Survey_Accelerator_Onboarding_Company` which lists `custom_102`..`108`).
  The new profile `EU_Survey_VM_Fundraising_Company` is shipped with the match
  rules and `organization_name`; the numeric ids of the `EIC_Fundraising_Assessment`
  fields must be added to its `fill_fields` **after the custom fields are created
  on the environment** (their ids are assigned by CiviCRM at reconcile). Until
  then the FP passes the values but XCM will not persist the custom fields.
  Profile lives in `assets/settings/xcm_config_profiles.json` (single source of
  truth, pushed by the extension).

## 12. Confirmations — all resolved

1. **CONFIRMED** — New-company subtype: `EIC_Organisation`. Matches the existing
   `eic_company_import` convention (XCM `eu_survey_company`, `contact_type:
   EIC_Organisation`).
2. **CONFIRMED** — Founder contact XCM: reuse `eu_survey_individual` as-is
   (matched on email).
3. **CONFIRMED** — Sector option group: **new** (`eic_vm_sector_cluster`),
   distinct from the onboarding sector.
4. **CONFIRMED** — Amount fields currency: Money, EUR.
5. **CONFIRMED** — Field types per §7, with these changes:
   - #19 Date — removed (being removed from the form).
   - #21 Links accessible confirmation — removed (not imported).
   - #22 renamed to **Dealroom Profile Link / Dealroom Company ID**.
   - #24 Free Text Question — removed (dropped).
