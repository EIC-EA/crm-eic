# EIC SRM — User Guide (for BAS contractors)

This guide explains the EIC Stakeholder Relationship Management platform (EIC SRM) for the people who use it day to day: the EIC Business Acceleration Services (BAS) contractors. It is **generic** — it describes what is common to every BAS programme, not the specifics of any one service.

It covers:

1. What the platform is, in one picture

2. What information you can access

3. How to create and modify a Contact

4. How to create and modify a Relationship

5. How to use Tags

6. The beneficiary onboarding workflow (common to all programmes)

7. What to do when a Service Request is created for you


## 1. What the platform is, in one picture

The EIC SRM is a shared CiviCRM database that gives a single, up-to-date view of each EIC beneficiary and the support they receive across all BAS services.

The core idea:

- An **EIC Awardee** (an organisation) is any company eligible for EIC Business Acceleration Services — not only companies with EIC grant funding. See "Which companies are EIC Awardees" below for the schemes covered.

- Its EIC-supported work is recorded as one or more **EIC Awardee Project** records.

- The people who represent the company (coordinator, LEAR, main contact...) are **contacts**, linked to the company by a **relationship** that says what their role is.

- A **KAM** (Key Account Manager) owns the relationship with the company. Other internal EIC roles (e.g. Project Officer, Programme Manager) are planned — see the relationships section.

- Work you do with a company is tracked through **Cases** and logged as **Activities** (emails, calls, meetings, tasks).

- **Tags** mark which BAS programme(s) a company belongs to.

### Which companies are EIC Awardees

"EIC Awardee" covers every company that can receive BAS services, across several EIC schemes — grant-funded and non-grant alike. This includes: EIC Accelerator, EIC Pathfinder, EIC Transition, EIC STEP Scale-Up, the SME Instrument / EIC Pilot legacy schemes, FET Open/Proactive, Fast Track to Innovation, **Seal of Excellence** holders (recorded via the project *Category*, since they come through different schemes), EIC Prizes, EIC Booster, European Innovation Ecosystems (incl. WomenTechEU), and — as they are added — EIC Ukrainian Tech, EIC Scaling Club, and EIC Pre-Accelerator.

> Data availability today vs. later — see the note at the end of section 2.

You mostly **read** beneficiary information that already exists (imported from EC systems and from the beneficiary's onboarding survey), and you **add** the interactions and outcomes of your own service.


## 2. What information you can access

### The main things you will see on a beneficiary

- **Company (EIC Awardee) profile** — name, PIC number, website, domain, and EC-sourced attributes (SME/mid-cap status, legal status, public body, etc.).

- **Self-Assessed information** — sector, CEO/founder gender, and readiness levels (TRL, CRL, BRL, FRL) that the company declared in its onboarding survey. These are read-only.

- **Projects** — the EIC Awardee Project record(s): project number, title, funding scheme, dates, sector classification, partners.

- **Contacts** — the people linked to the company and their roles.

- **Cases** — the onboarding case and any Service Request cases.

- **Activities** — the history of interactions (emails, calls, meetings, tasks) logged against the company or its cases.

### Access is scoped by BAS programme

Access is organised around **BAS programme groups**. Each BAS programme has its own group used for security, so you see the beneficiaries and information relevant to your programme. If you believe you are missing access to something you need, raise it with the EIC/EISMEA team rather than assuming it does not exist.

> Note: exact visibility rules (who can see what across programmes) are being finalised. This guide describes the model; your effective access is set by your account's group membership.

### What data is available today vs. later

The platform is populated progressively. What is available depends on which source data has been imported so far.

**Available now** (imported from EC systems):

- Company profile: PIC, legal name, address, website, and legal-status attributes (SME/mid-cap, research organisation, public body, etc.).

- Project records: proposal number, title, acronym, funding scheme, category (incl. Seal of Excellence), dates, sector classification, consortium roles (coordinator/partners), and status.

- Representatives and their roles (CoCo, PaCo, PCoCo, LEAR).

- Self-Assessed information (sector, TRL/CRL/BRL/FRL) once a beneficiary completes the onboarding survey.

**Coming later** (not imported yet):

- Additional schemes not yet in the source extracts — notably **EIC Ukrainian Tech**, **EIC Scaling Club**, and **EIC Pre-Accelerator**.

- Internal EIC team roles beyond the KAM — **Project Officer** and **Programme Manager**.

If a company or scheme you expect is not visible yet, it may simply not have been imported. Raise it with the EIC/EISMEA team.


## 3. How to create and modify a Contact

A **Contact** is either an **Organisation** (a company) or an **Individual** (a person).

### Create

1. Go to **Contacts → New Organisation** (or **New Individual**).

2. Choose the correct **contact type / sub-type**:

   - **EIC Awardee** — a funded/selected beneficiary organisation.

   - **EIC Awardee representative** — a person officially listed for an EIC project.

   - (**Investor** / **Investor Representative** exist for VentureMatch investor-side records.)

3. Fill in the name and the fields you know. For a company, the **PIC number** is the key identifier — do not invent one; leave it blank if unknown.

4. Save.

### Modify

- Open the contact and use **Edit**.

- Some fields are **read-only** (greyed out) — for example the *Self-Assessed information* (sector, TRL/CRL/BRL/FRL) that comes from the onboarding survey. These are maintained by the platform, not edited by hand.

### Good practice

- **Search before you create** to avoid duplicates — especially for companies (search by PIC or name) and people (search by email).

- Record a person's role in the organisation in their **job title**.


## 4. How to create and modify a Relationship

A **Relationship** links a person to a company (or a company to a case) and says what the link means. This is how the platform knows who the coordinator, the LEAR, or the main BAS contact of a company is.

### The relationships you will use most

| Relationship | Meaning |
| - | - |
| **Main EIC BAS Contact** | The main contact for the company regarding BAS services. |
| **EIC BAS Contact** | An additional BAS contact (a company can have several). |
| **KAM** | The Key Account Manager (internal EIC role) responsible for the company. |
| PCoCo / CoCo / PaCo / LEAR | EU Funding & Tenders Portal roles (usually imported, rarely edited by hand). |


**Internal EIC roles.** The **KAM** is the internal EIC role available today. **Project Officer** and **Programme Manager** are planned as additional internal roles but are not in the platform yet — they will be added as new relationship types linking the EIC team member to the company.

### Create

1. Open the **person's** contact record.

2. Go to the **Relationships** tab → **Add Relationship**.

3. Choose the relationship type (e.g. *Main EIC BAS Contact for*).

4. Select the **company** (the EIC Awardee) as the other party.

5. Save.

You can add these by hand at any time — for example to record that a person is your main BAS contact at a company. The BAS contact relationships are open for you to manage.

### Modify / end

- Open the relationship and edit it, or set an **end date** to close it (rather than deleting) so the history is kept.


## 5. How to use Tags

**Tags** are labels you can attach to companies, contacts, activities and cases to organise and find them.

### The BAS programme tag model

- There is **one parent tag per BAS programme** (e.g. *EIC VentureMatch*, *EIC Coaching*, *EIC Corporate Partnership*, ...). These parent tags are managed centrally.

- **You can create your own child tags** underneath your programme's parent tag, to organise beneficiaries the way your service needs (e.g. cohorts, themes, priority). The tag structure is deliberately **open** for contractors to extend.

### Create a child tag

1. Go to **Administer → Tags (Tag Sets)** (or use the tag picker on a contact and choose to add a new tag).

2. Create your tag and set its **parent** to your BAS programme tag.

3. Use it by attaching it to contacts / cases / activities.

### Good practice

- Keep your child tags under your programme's parent tag so the structure stays clean and shared reporting keeps working.

- Prefer a small, meaningful set of tags over many one-off tags.


## 6. The beneficiary onboarding workflow (common to all programmes)

This is the shared journey every beneficiary goes through. Most of it is **automatic** — it is useful to understand what happens so you know what to expect and what is yours to act on.

### The journey

1. **Selection / funding.** A company selected or funded by the EIC is recorded as an **EIC Awardee**, and its project as an **EIC Awardee Project**.

2. **KAM assigned.** A Key Account Manager is linked to the company.

3. **Onboarding case + survey.** An **EIC Awardee Onboarding** case tracks the process. The beneficiary receives and completes an **EU-Survey**.

4. **Survey import (automatic).** When the completed survey is imported, the platform:

   - stores the answers and a snapshot of the company and main contact,

   - updates the company's **Self-Assessed information** (sector, TRL/CRL/BRL/FRL),

   - moves the onboarding case to **Onboarded**,

   - opens a **Service Request** case for each BAS programme the company asked for.

### What is automatic vs. what you do

| Step | Who |
| - | - |
| Awardee, Project, survey answers, Onboarded status, Service Request creation | **Automatic** (platform) |
| Delivering the service, logging interactions, updating the Service Request status | **You** (BAS contractor) |


So by the time a beneficiary reaches you, the company profile, project, self-assessed data and the Service Request are already in place. Your job starts at the Service Request.


## 7. What to do when a Service Request is created for you

When a beneficiary asks for your service in the onboarding survey, the platform automatically opens a **Service Request** case for your programme, linked to the company and to the originating survey. The Case Coordinator is left empty at creation.

**For now, what you need to do is:**

1. **Log your interactions** with the beneficiary using the **existing activities** — Email, Phone Call, Meeting, Follow up, Task — on the company or on the Service Request case. This keeps a shared, up-to-date history of the support provided.

2. **Update the Service Request case status** as you progress, and in particular **when you have provided the service**, so everyone can see where the beneficiary stands.

That is the common baseline expected of every programme today. The more detailed, programme-specific steps (who exactly picks up the case, intermediate statuses, data you capture) are being defined **with you** — this is the purpose of the needs-gathering exercise. Until those are agreed, logging activities and keeping the Service Request status current is what matters.


## Where to get help

- For **access** questions (you cannot see a beneficiary or a programme you should): contact the EIC/EISMEA team.

- For **missing information or functionality** (a field or capability you need that does not exist): note it down — it feeds the needs-gathering exercise that shapes the next releases.

