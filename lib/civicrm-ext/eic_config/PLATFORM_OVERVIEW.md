# EIC SRM Platform — What it does, in business terms

This is the business-oriented map of our CiviCRM. It is written for a person (a new colleague, an onboarding user) and for an AI connected to the platform, so both can understand **what each object means** and **what happens automatically**, without reading code.

## The principle: the platform explains itself

Every meaning and every automatic behaviour described here should also live **inside the object it describes**, so it is visible in the CiviCRM UI and readable through the API:

- **Types and statuses** (contact types, activity types, relationship types, case types, case statuses) carry their meaning and behaviour in their own `description`.
- **Custom fields** carry their meaning in their field help — `help_pre` (shown above the field) and `help_post` (shown below). See `CUSTOM_FIELD_REQUEST.md` for the field catalogue and the rule that a field is not created until its help text is written.

This file is the master copy. The **"Text to put in the platform"** boxes below are the exact strings to paste into each object's `description`, so the whole app stays coherent. The rule: *a config change that is not reflected in a description or help text is incomplete.*

Where a behaviour is implemented in code, the source is named so the description and the reality stay in sync. The authoritative technical reference for the survey automation is `eic_eu_survey_form_processor/README.md`; this file is the business summary that sits on top of it.

## 1. The story in one paragraph

A company becomes an **EIC Awardee** when it is selected or funded by the EIC. Its funded projects are recorded as **EIC Awardee Project** activities. The people who represent the company (coordinators, LEAR, etc.) are **EIC Awardee representatives**, linked to the company through **relationship types** — some mirror the EU Funding & Tenders Portal roles, others are the EIC Business Acceleration Services (BAS) contacts. When a company is onboarded, an **EIC Awardee Onboarding case** (one per project) tracks the process, with a **KAM** (Key Account Manager) as the case manager. The beneficiary fills an **EU-Survey**; importing that survey writes the answers into the platform, updates the company's self-assessed data, **moves the onboarding case to `Onboarded`**, and opens a **Service Request** case for each EIC BAS programme the company asked for.

## 2. Contact types (who is in the system)

| Type | Parent | Meaning |
| - | - | - |
| **EIC Awardee** | Organization | Organisation selected, funded, or recognised by the EIC. |
| **EIC Organisation** | Organization | An organisation created during an automatic import that is not (yet) recognised as an EIC Awardee. |
| **Investor** | Organization | An investor organisation (e.g. for VentureMatch). |
| **EIC Awardee representative** (`EIC_Registered`) | Individual | Person officially listed for an EIC project (coordinator, LEAR, participant contact...). |
| **Investor Representative** | Individual | A person representing an investor organisation. |

These are largely self-explanatory, so a one-line `description` on each is enough. Confirm each of the five actually has a description; add the missing ones.

> **Text to put in the platform** (Contact type `description`):
> - **EIC Organisation** — "An organisation created during an automatic import that is not recognised as an EIC Awardee. Promoted to EIC Awardee if and when it is confirmed as a funded/selected beneficiary."
> - **Investor** — "Investor organisation engaged with the EIC (e.g. through VentureMatch). Represented by one or more Investor Representative contacts."

## 3. Relationship types (how people connect to companies)

All of these link an **Individual** to an **Organisation**. They fall into three families: the EU Funding & Tenders Portal roles (PCoCo, CoCo, PaCo, LEAR, Other member), the EIC Business Acceleration Services contacts (Main EIC BAS Contact, EIC BAS Contact), and the internal account role (KAM). Each already carries a `description` in code — keep those as the single source of truth for "who is who".

| Role (label) | Machine name | Family | Meaning |
| - | - | - | - |
| PCoCo — Primary Coordinator Contact | `EIC_PCoco_For` / `EIC_PCoco_Is` | F&TP portal | Main contact between the consortium and the EU for a project/contract. |
| CoCo — Coordinator Contact | `EIC_Coco_For` / `EIC_Coco_Is` | F&TP portal | Added by the PCoCo for the project/contract (any number). |
| PaCo — Participant Contact | `EIC_PaCo_For` / `EIC_PaCo_Is` | F&TP portal | Representative of a consortium organisation that is not the coordinator. |
| LEAR — Legal Entity Appointed Representative | `EIC_LEAR_For` / `EIC_LEAR_Is` | F&TP portal | Nominated main responsible for the organisation's use of the Funding & Tenders Portal. |
| EIC Other Project member | `EIC Project member of` / `EIC Project member is` | F&TP portal | Part of an EIC project with a role other than PCoCo, CoCo or PaCo. |
| Main EIC BAS Contact for / is | `Main contact for` / `Main contact is` | EU-Survey (survey ext) | The main EIC BAS contact for the organisation. Usually the primary contact from Section 2 of the onboarding survey, but can also be added manually. Individual → Organisation. Role stored on the Individual's `job_title`. |
| EIC BAS Contact for / is | `Contact for` / `Contact is` | EU-Survey (survey ext) | An additional EIC BAS contact (survey allows contacts 2–4, up to three), or added manually. Individual → Organisation. Role stored on the Individual's `job_title`. |
| **KAM — Key Account Manager** | `EIC_KAM_For` / `EIC_KAM_Is` | Internal | EIC staff member responsible for the account. Used as the **case manager** role on the EIC Awardee Onboarding case. |

Notes worth surfacing to a business user:

- **Naming.** The two survey-contact relationships are labelled **Main EIC BAS Contact** and **EIC BAS Contact** so it is clear they concern EIC Business Acceleration Services. The underlying machine names stay `Main contact for` / `Contact for` (unchanged), because the form processors and the `eic_awardee_onboarding` case roles reference them by machine name — only the display label changed.
- **These can be added manually.** They are usually created by the survey import, but a user can add either relationship by hand on any contact.
- **Reference by machine name, never by label or id.** When an automation creates any of these relationships, it passes the relationship type **machine name** (e.g. `EIC_KAM_For`, `Main contact for`, `Contact for`) directly to the `CreateOrUpdateRelationship` action. No numeric id lookup is needed. This is what keeps automations stable when a display label is renamed.
- The KAM relationship carries a behaviour, not just a definition, so its description should say so.

> **Text to put in the platform** (KAM relationship `description`): "Key Account Manager responsible for the organisation. On an EIC Awardee Onboarding case the KAM is the case manager (manager role `EIC_KAM_Is`). The onboarding case import finds the KAM by email (`kam_email`) and creates this relationship scoped to that case."

The **Main EIC BAS Contact** and **EIC BAS Contact** descriptions already live in their managed files (`eic_eu_survey_form_processor/managed/RelationshipType_MainContact.mgd.php` and `RelationshipType_Contact.mgd.php`) and state that they can be added manually and that the role is stored on `job_title`.

## 4. Activities (the events and the imported data)

### 4.1 EIC Awardee Project (`EIC_Awardee_Project`)

This activity **is** the project record — one per funded project. It carries the Horizon Europe project information (project number, dates, funding, sectors, etc.; see `CUSTOM_FIELD_REQUEST.md`). It already has a good description covering EIC Awardees & Grantees and Seal of Excellence holders — keep it.

### 4.2 EIC Accelerator Onboarding Survey Data (`eic_accelerator_onboarding_survey`)

This activity **is** one submitted EU-Survey (Accelerator scheme). The survey import creates it, attaches it to the matched onboarding case, and stores every answer on it, plus a collapsed snapshot of the company and main-contact data. It is the audit trail of what the beneficiary declared.

> **Text to put in the platform** (Activity type `description`): "One submitted EU-Survey onboarding response (Accelerator scheme). Created automatically by the EU-Survey import: it holds every survey answer plus a snapshot of the company and main-contact data at submission time. Importing it also updates the company's self-assessed data, moves the linked onboarding case to Onboarded, and opens the Service Request cases the company asked for."

## 5. Cases (the processes we run) — where behaviour matters most

Automatic transitions belong in the case type description so nobody has to read code to know what a case does.

Two extensions ship case types:

**Shipped by `nc_config`** (general engagement/VentureMatch lifecycle):

| Case type | Machine name | Case manager role | What it tracks |
| - | - | - | - |
| Investor Onboarding | `eic_investor_onboarding` | Case Coordinator | Onboarding an investor (Outreach → Onboarding timeline). |
| Engagement | `eic_engagement` | Case Coordinator | General engagement/outreach timeline. |
| VentureMatch Beneficiary Onboarding | `eic_vm_beneficiary_onboarding` | Case Coordinator | Onboarding a beneficiary into VentureMatch (Outreach → Onboarding). |
| VentureMatch Beneficiary Support | `eic_vm_beneficiary_support` | Case Coordinator | Ongoing support (Planning → Execution). |

**Shipped by `eic_eu_survey_form_processor`** (the survey-driven onboarding):

| Case type | Machine name | Case manager role | What it tracks |
| - | - | - | - |
| **EIC Awardee Onboarding** | (survey extension) | **KAM** (`EIC_KAM_Is`) | Onboarding a funded beneficiary. **One case per project** (matched on PIC + case title + EIC Project ID). |
| Service Request — one per BAS programme (x10) | `eic_sr_*` | Case Coordinator (left empty at creation) | A request for a specific EIC business-support service. Each links back to the originating survey activity. |

The 10 Service Request programmes: VentureMatch, Coaching, Ecosystem Partnership, Global Business Expansion, Innovation Procurement, Women Leadership Programme, InnoNext, Corporate Partnership, International Trade Fairs, and Community (shipped but not yet triggered).

### Case statuses

`Onboarded` (value `6`) and `Declined` (value `5`) are custom **closed** statuses (`nc_config/managed/040_CaseStatuses.mgd.php`). `Onboarded` is the successful end state of an onboarding case.

### The key automatic behaviour (the survey-import example)

When the EU-Survey activity is imported and attached to a matching EIC Awardee Onboarding case, the import **sets that case's status to `Onboarded`** (via the `UpdateCaseStatus` action, logging a "Change Case Status" activity). It only runs when a matching survey case is found. Status value `6` is a forced managed OptionValue, so it is deterministic across environments. Implemented in the `EIC Accelerator Onboarding Survey Import` form processor.

> **Text to put in the platform** (Case type **EIC Awardee Onboarding** `description`): "Tracks onboarding of a funded EIC beneficiary — one case per project (matched on company PIC + case title + EIC Project ID). The KAM is the case manager. When the beneficiary's EU-Survey is imported and matched to this case, the case is automatically set to Onboarded and a Service Request case is opened for each support programme the company requested. Created and matched by the EU-Survey onboarding import."

> **Text to put in the platform** (each Service Request `eic_sr_*` case `description`): "Request for the <programme name> EIC business-support service. Opened automatically by the EU-Survey onboarding import when the beneficiary's answer to the <programme> question triggers it. Links back to the originating EU-Survey activity. Case Coordinator is intentionally empty at creation."

> **Text to put in the platform** (Case status **Onboarded** `description`): "Successful end state of an onboarding case. Set automatically when the beneficiary's EU-Survey is imported and matched to the case."

> **Done** — the four `nc_config` case types previously had a description equal to their title. They now carry a one-line purpose derived from their activity timelines (in `nc_config/managed/050_CaseTypes.mgd.php`): Investor Onboarding (Outreach → Onboarding), Engagement (Expression of Interest → Introduction follow-ups), VentureMatch Beneficiary Onboarding (Outreach → Onboarding survey + call), VentureMatch Beneficiary Support (Planning → Execution). All four use Case Coordinator as the case manager.

## 6. What happens when an Accelerator survey is imported (end to end)

This is the single most important automated flow. Documented once here; each object's description points at its part.

1. **Company** is matched or created by PIC. PIC is never overwritten once set; website is additive.
2. **Contacts** (main + up to 3 additional) are matched or created and linked to the company via the BAS relationships (machine names `Main contact for` / `Contact for`); each contact's role is stored on `job_title`.
3. A **Survey activity** (`eic_accelerator_onboarding_survey`) is created holding all answers + company/contact snapshots.
4. **Company self-assessed data** (CEO/founder gender, sector, TRL/CRL/BRL/FRL) is written onto the Organisation. These fields are **view-only**; the multi-selects (sector, TRL/CRL/BRL/FRL) **cumulate** values across all the company's projects. Readiness long labels are normalised to short codes (e.g. `TRL 4`).
5. The same self-assessed data is written onto the matched **EIC Project** activity as single values for that specific project.
6. The matched **EIC Awardee Onboarding case** is set to **Onboarded**.
7. A **Service Request case** is opened for each BAS programme whose survey answer triggered it.

Full technical detail (field mappings, XCM profiles, trigger conditions, and the machine-name relationship rule) lives in `eic_eu_survey_form_processor/README.md`.

## 7. How we keep this coherent (the working rule)

- **Types and statuses** document themselves through their `description`. When you add or change one, update its description in the same change. A behaviour (like an automatic status change or auto-created case) goes in the description of the object the user looks at — usually the case type.
- **Custom fields** document themselves through `help_pre` / `help_post`. A field is not created until its help text is written (see `CUSTOM_FIELD_REQUEST.md`).
- **Automations** (form processors, action provider) are the source of behaviour. Whenever an automation changes a status, creates a case, or writes a field, the affected object's description must say so in plain language, and this overview must be updated. Automations reference relationship types and other config **by machine name**, so a display-label rename never breaks them.
- **One change, one update.** A config change not reflected in a description or help text is incomplete.

## 8. Quick reference — where each thing is defined

| Thing | Defined in |
| - | - |
| Contact types | `eic_config/managed/0010`, `0100*`, `0110*` |
| Relationship types (PCoCo, CoCo, PaCo, LEAR, member, KAM) | `eic_config/managed/0300`–`0350*` |
| BAS relationship types (Main EIC BAS Contact, EIC BAS Contact) | `eic_eu_survey_form_processor/managed/RelationshipType_MainContact.mgd.php`, `RelationshipType_Contact.mgd.php` |
| EIC Awardee Project activity type | `eic_config/managed/0200_CustomOptionValue_EIC_Awardee_Project` |
| Awardee custom fields | `eic_config/managed/0400`–`0440*` (catalogued in `CUSTOM_FIELD_REQUEST.md`) |
| Case types & statuses (Investor/Engagement/VentureMatch; Onboarded/Declined) | `nc_config/managed/050_CaseTypes`, `040_CaseStatuses` |
| EIC Awardee Onboarding & Service Request case types, survey activity, snapshots, all survey automation | `eic_eu_survey_form_processor/` (see its README) |
| Reusable actions/conditions (case status, get case/activity by name) | `eic_fp_action_provider/`, `action-provider` |
