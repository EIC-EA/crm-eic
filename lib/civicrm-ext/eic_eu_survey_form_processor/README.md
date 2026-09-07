EIC EU-SURVEY FORM PROCESSORS
=============================

Notes
=====

**This Extension can now be used as all mandatory functionality is available.**

Open
----

- Merge existing XCM profiles with loaded XCM profiles.
- Update this README in order to reflect all current fields of ḾanagedEntities and Form-Processors.

Description
===========

This extension does the following:

- load `Managed Entities` stored in `managed` folder upon extension installation or after (re)enabling the extension
- load `FormProcessors` stored in `assets/form-processor` folder upon extension installation or after (re)enabling the extension
- load `XCM` profiles stored in `assets/xcm` folder up upon extension installation or after (re)enabling 

**Note**

A cache clear won't (re)trigger the loading of `FormProcessor` configuration. Only installation ot (ee)enabling the extension does.

Settings
========

The extension has a simple configuration settings page, to be accessed under:

- civicrm menu -> Administer -> System Settings -> Settings for EIC EU-Survey Form-Processor Automation
- `civicrm/admin/setting/eic_eu_survey_form_processor`

The following settings are available:

**Import Form-Processors if already existing?**

If this option is enabled, then the Form-Processor configuration that is shipped with this extension
will be imported if a form-processor with the same name **exists already**. This will overwrite all
changes made to that form-processor.

If this option is turned off, then the avaiable form-processor configuration will only be imported if
no form-processor with the same name does already exists.

**Import CiviCRM Settings if already existing?**

If this option is enabled, then the CiviCRM Settings configuration that is shipped with this extension
will be imported if a setting with the same name **exists already**. This will overwrite all
changes made to that setting.

If this option is turned off, then the avaiable CiviCRM Settings configuration will only be imported if
no setting with the same name does already exists.

Dependencies
============

The following CiviCRM Extensions must have been installed before installing this extension:

| Repo                           | Extension                                                                           | Notes                                                                               |
|--------------------------------|-------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------|
| Extended Contact Matcher       | [de.systopia.xcm](https://github.com/systopia/de.systopia.xcm)                      | Provides means to find and create contacts and provides `form_processors` actions   |
| Action Provider                | [action_provider](https://lab.civicrm.org/extensions/action-provider)               | provides all common actions for `form_processor` extension                          |
| Form Processor                 | [form_processor](https://lab.civicrm.org/extensions/form-processor)                 | provides means to receive and process arbitrary form data                           |

The following CiviCRM Extension can be additionally installed

| Extension                      | Repo                                                                                |  Notes                                                                              |
|--------------------------------|-------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------|
| Advanced Importer              | [advimport](https://lab.civicrm.org/extensions/advimport)                           | Allows csv-file import                                                              |
| Advanced Import Form Processor | [advimportformprocessor](https://lab.civicrm.org/extensions/advimportformprocessor) | Allows to send csv-file data to a form-processor                                    |

Installation
============

- Install mandatory Extensions first.
- Use the standard way of installing CiviCRM Extensions from a repository.

Check after Installation or after (Re)Enabling
===============================================

Once the extension has been installed or (re)enabled, please check if all entities that has been loaded
as `ManagedEntities` are available in CiviCRM and are enabled:

- the `ActivityType` _EU Survey data_ will sometimes be disabled for yet unknown reasons. Re-enable that acticity type under `/civicrm/admin/options/activity_type`
- check if all CustomGroups are enabled and also if their fields are enabled under `/civicrm/admin/custom/group`
- check if `CaseType` _EU Survey Import_ is available and enabled under `/civicrm/a/#/caseType`
- check if `RelationshipType` _Applicant for_ is available and enabled under `/civicrm/admin/reltype`

Folder Structure
================

managed
-------

Contains all ManagedEntities that will be loaded upon installation or (re)enabling of extension.

| ManagedEntity         | Value                                                           |
|-----------------------|-----------------------------------------------------------------|
| CaseType              | `EU Survey Import`                                              |
| OptionGroup           | contains Activity `EU Survey Data` (value=67)                   |
| RelationshipType      | `Applicant For` for `Contact`                                   |
| CustomGroup           | `EIC Project` for `Case` of case type `EU Survey Import`        |
| CustomGroup           | `EU Survey Data` for `Activity` of activity type `EU Survey`    |
| CustomGroup           | `EU_Survey_Company_Data` for `Contact` of type `Organisation`   |

assets
------

Contains all assets that will be loaded upon installation or (re)enabling of extension.

**Form Processor Configuration**

| Form Processors       | Description                                                                                       |
|-----------------------|---------------------------------------------------------------------------------------------------|
| EIC Applicant Import  | Create applicant contacts from EU-Survey dataset                                                  |
| EIC Company Import    | Create beneficiary companies from EU-Survey dataset                                               |
| EIC Case Import       | Create cases of type _EU-Survey Import_ from EU-Survey dataset                                    |
| EIC Default Case      | Create a default case type _EU-Survey Import_ for catching failures during EU-Survey data import  |
| EU Survey Import      | Created activites of type _EU-Survey data_ and assign all EU-Survey data to activity  |

**CiviCRM Settings**

Currently, only the setting for the XCM Extension is imported. That setting contains XCM-Profiles that
can be accessed under `/civicrm/admin/setting/xcm`. The imported profiles are used by the Form-Processors
and must therefore be available in the system.

| Settings              | Description               |
|-----------------------|---------------------------|
| xcm_config_profiles   | contains XCM Profiles:    |
|                       | _EU Survey - Company_     |
|                       | _EU Survey - Applicant_   |

Mapping EU Survey Fields to CiviCRM Entities
============================================

Types
-----

The following types are made available through `ManagedEntities`.

| for Entitiy | Type                    |
|-------------|-------------------------|
| Activity    | _EU-Survey Data_        |
| Case        | _EU-Survey Import_      |


Case - CustomFields
-------------------

The following custom-groups and fields are made available through `ManagedEntities`.

| **CustomGroup**  | Title                   | Name                    |
|------------------|-------------------------|-------------------------|
|                  | EIC Project Data        | EIC_Project             |

| **CustomFields** | Label                   | Name                    |
|------------------|-------------------------|-------------------------|
|                  | EIC Title               | EIC_Title               |
|                  | Organisation PIC number | Organisation_PIC_number |


Activity - Custom Fields
------------------------

| **CustomGroup**  | Title                   | Name                    |
|------------------|-------------------------|-------------------------|
|                  | EU Survey Data          | eu_survey_data          |

| **CustomFields** | Label                                                                                                          | Name                                                              |
|------------------|----------------------------------------------------------------------------------------------------------------|-------------------------------------------------------------------|
|                  | Pitch Deck                                                                                                     | survey_latest_pitch_deck                                          |
|                  | EIC Project ID                                                                                                 | survey_eic_project_id                                             |
|                  | EIC Project Accronym                                                                                           | survey_eic_project_acronym                                        |
|                  | Are you fundraising within the next 18 months                                                                  | survey_fundraising_within_the_next_18_months                      |
|                  | Are you fundraising within:                                                                                    | survey_fundraising_within                                         |
|                  | What fundraising support do you need most                                                                      | survey_receive_suport_fundraising_items                           |
|                  | Would you like to receive investment support                                                                   | survey_receive_support_investment                                 |
|                  | Are you actively seeking corporate or industrial partners                                                      | survey_partenrs_actively_seeking                                  |
|                  | What type of partner are you targeting                                                                         | survey_partners_types                                             |
|                  | Are you planning to sell your innovative solution to public or private buyers                                  | survey_selling_to_public_private_buyers                           |
|                  | Where are you in the process                                                                                   | survey_selling_to_public_private_buyers_where_are_you_in_the_...  |
|                  | Would you like procurement support or training                                                                 | survey_receive_support_procurement                                |
|                  | Are you planning to expand internationally in the next 18 months                                               | survey_international_expansion_in_the_next_18_months              |
|                  | Which markets                                                                                                  | survey_international_expansion_markets                            |
|                  | Would you like to receive support entering new markets                                                         | survey_receive_support_entering_new_markets                       |
|                  | What is your main barrier                                                                                      | survey_international_expansion_main_barrier                       |
|                  | Are you planning to participate in International trade fairs in the next 12 months                             | survey_international_trade_fairs_participate_in_the_next_12_m...  |
|                  | Which region                                                                                                   | survey_international_trade_fairs_region                           |
|                  | Type of events                                                                                                 | survey_international_trade_fairs_events                           |
|                  | Would you benefit from industry experts or coaches support                                                     | survey_receive_support_industry_experts_or_coaches                |
|                  | What type of support                                                                                           | survey_receive_support_industry_experts_or_coaches_types          |
|                  | What is your main challenge?                                                                                   | survey_main_challenge                                             |
|                  | Does your company have a woman founder or executive who would benefit from a dedicated leadership programme    | survey_Does_your_company_have_a_woman_founder_or_executive_wh...  |
|                  | What type of other support are you interested in                                                               | survey_receive_support_other_types                                |
|                  | Are there any other support items you would like to benefit from                                               | survey_receive_support_other_items                                |
|                  | General feedback                                                                                               | survey_feedback                                                   |
|------------------|----------------------------------------------------------------------------------------------------------------|-------------------------------------------------------------------|

Contact(Organisation) - Custom Fields
-------------------------------------

| **CustomGroup**  | Title                   | Name                    |
|------------------|-------------------------|-------------------------|
|                  | EU Survey Company Data  | eu_survey_company_data  |

| **CustomFields** | Label                                                                                                          | Name                                                              |
|------------------|----------------------------------------------------------------------------------------------------------------|-------------------------------------------------------------------|
|                  | CEO or project leader gender                                                                                   | CEO_or_project_leader_gender                                      |
|                  | Founder Gender                                                                                                 | Founder_Gender                                                    |


EU Survey Fields mapped to standard fields of Entities
------------------------------------------------------

| **EU Survey Field**          | Field Name               | Entity                              | Notes                       |
|------------------------------|--------------------------|-------------------------------------|-----------------------------|
|                              | First name               | Individual.first_name               |                             |
|                              | Last name                | Individual.last_name                |                             |
|                              | Phone number             | Individual.phone                    |                             |
|                              | Professional email       | Individual.email_primary            |                             |
|                              | Role in organisation     | Individual.job_title                |                             |
|                              | Company Website          | Organisation.website                |                             |
|                              | Organisation name        | Organisation.organization_name      |                             |
|                              | Organisation PIC number  | Organisation.external_identifier    |                             |


Available FormProcessors
========================

Import Company Data
-------------------

**Notes on identitifying a company**

This FormProcessor just creates organisation contacts. The `External Identifier` field of an organisation contact
will be used to store the `PIC Number`. That number should uniquely identify a company later on, when attaching
cases to the company.

**Form Processor**

- Title: `EIC Company Import`
- Name: `eic_company_import`

**Fieldmapping**

| EU Survey Field                 | Form Processor Input Field name  | Data Type   | Notes     |
|---------------------------------|----------------------------------|-------------|-----------|
| PIC number                      | org_pic_number                   | short text  |           |
| Company Name                    | org_name                         | short text  |           |
| Website                         | org_website                      | short text  |           |

Import Cases
------------

**Notes on Case to Company retaionship**

- This FormProcessor creates a case and sets the case title to `EIC Awardee Onboarding`.
- It will link a company by its `PIC Number` as client to the newly created case.

Later on, when EU Survey data is being imported:

- An organisation's `PIC Number` can be used to identitfy the organisation belonging to the case.
- The case title can be used to identify the proper case as a company may have more than one case it is a client of.

**Form Processor**

- Title: `EIC Case Import`
- Name: `eic_case_import`

**Fieldmapping**

| EU Survey Field                 | Form Processor Input Field Name | Data Type     | Notes         |
|---------------------------------|---------------------------------|---------------|---------------|
| EIC Project Acronym             | case_eic_project_acronym        | short text    | not used yet  |
| EIC Project ID                  | case_eic_project_id             | short text    | not used yet  |
| PIC Number                      | org_pic_number                  | short text    |               |

Import Applicant Data
---------------------

**Notes on creating applicant contacts**

This FormProcessor is mainly for test purposes. Applicant contacts will be created upon importing EU Survey data
if no contact yet exists that match the provided applicant data.

With this FormProcessor one can already import some applicants as contacts and test during EU Survey data import
if the contact matching works properly. Then, no contacts will should be created if already existing.

**Form Processor**

- Title: `EIC Applicant Import `
- Name: `eic_applicant_import`

**Fieldmapping**

| EU Survey Field                 | Form Processor Input Field Name | Data Type     | Notes     |
|---------------------------------|---------------------------------|---------------|-----------|
| First Name                      | ind_first_name                  | short text    |           |
| Last Name                       | ind_last_name                   | short text    |           |
| Professional Email              | ind_professional_email          | short text    |           |
| Phone                           | ind_phone                       | short text    |           |
| Role                            | ind_role                        | short text    |           |

Import EU-Survey Data
---------------------

This FormProcessor creates an activity that stores the received EU Survey data.
The activity will be attached to a case.

The case will be identified by:

- the received  `PIC Number`, that is matched to the corresponding custom field of a case
- the case title, which is supposed to be set to `EIC Awardee Onboarding`.

This FormProcessor also creates an individual contact from the transmitted applicant data
if no contact yet exists that match the recceived contact data (First Name, Last Name, Professional Email).

**Form Processor**

- Title: `EU Survey Import`
- Name: `eu_survey_import`

**Fieldmapping**

| EU Survey Field                                                                                             | Form Processor Input Field Name                                                 | Data Type         | Notes       |
|-------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------|-------------------|-------------|
| PIC number                                                                                                  | org_pic_number                                                                  | short text        |             |
| EIC Project ID                                                                                              | case_eic_project_id                                                             | short text        |             |
| First Name                                                                                                  | ind_first_name                                                                  | short text        |             |
| Last Name                                                                                                   | ind_last_name                                                                   | short text        |             |
| Professional Email                                                                                          | ind_professional_email                                                          | short text        |             |
| Fundraising within the next 18 months                                                                       | survey_fundraising_within_next_18_months                                        | bool              | "Yes" "No"  |
| Are you fundraising within                                                                                  | survey_fundraising_within                                                       | short text        |             |
| What fundraising support do you need most                                                                   | survey_support_fundraising                                                      | long text         |             |
| Would you like to receive investment support                                                                | survey_support_investment                                                       | bool              | "Yes" "No"  |
| Are you actively seeking corporate or industrial partners                                                   | survey_actively_seeking_corporate_industrial_partnerts                          | bool              | "Yes" "No"  |
| What type of partner are you targeting                                                                      | survey_what_type_of_partner_are_you_targeting                                   | short text        |             |
| Are you planning to sell your innovative solution to public or private buyers                               | survey_plan_to_sell_solution_to_public_private_buyers                           | bool              | "Yes" "No"  |
| Where are you in the process                                                                                | survey_where_are_you_in_the_process                                             | long text         |             |
| Would you like procurement support or training                                                              | survey_support_training_procurement                                             | bool              | "Yes" "No"  |
| Are you planning to expand internationally in the next 18 months                                            | survey_plan_to_expand_internationally_within_next_18_months                     | bool              | "Yes" "No"  |
| Which markets                                                                                               | survey_expansion_markets                                                        | short text        |             |
| What is your main barrier                                                                                   | survey_expansion_main_barrier                                                   | short text        |             |
| Are you planning to participate in International trade fairs in the next 12 months                          | survey_plan_to_participate_in_international_trade_fairs_within_next_12_months   | bool              | "Yes" "No"  |
| Which region                                                                                                | survey_internation_trade_fairs_region                                           | short text        |             |
| Type of events                                                                                              | survey_international_ trade_fairs_type_of_events                                | short text        |             |
| Would you benefit from industry experts or coaches support                                                  | survey_would_support_industry_experts_coaches                                   | bool              | "Yes" "No"  |
| What type of support                                                                                        | survey_type_of_industry_export_support                                          | short text        |             |
| What is your main challenge                                                                                 | survey_main_challenge                                                           | long text         |             |
| Does your company have a woman founder or executive who would benefit from a dedicated leadership programme | survey_woman_founder_or_executive_benefit_from_a_dedicated_leadership_programme | bool              | "Yes" "No"  |
| What type of other support are you interested in                                                            | survey_suport_other_types                                                       | long text         |             |
| Are there any other support items you would like to benefit from                                            | support_support_other_items                                                     | long text         |             |
| Would you like to receive support entering new markets                                                      | survey_support_entering_new_markets                                             | bool              | "Yes" "No"  |
| General feedback                                                                                            | survey_general_feedback                                                         | long text         |             |

Import data via Form-Processor
==============================

Data Source
-----------

As input source, the CSV with the EU Survey extracts can be used with all Form-Processors listed above.

- https://eceuropaeu.sharepoint.com/:x:/r/teams/GRP-EICSRM/_layouts/15/Doc.aspx?sourcedoc=%7B1020AA29-6441-47BA-A5B8-DF13D0A326EF%7D&file=Export799826.xlsx&action=default&mobileredirect=true

Each Form-Processor just processes a subset of the CSV file columns.
Therefore, the same CSV file can be used for different import tasks.

See the description of each Form Processor for necessary EU-Survey Data.

Send data to Form Processor via CiviCRM Api3
============================================

The Form-Processor can be accessed via the CiviCRM **Api Version 3**.

- entity name: `FormProcessor`

Form Processor access via Api3 Action
-------------------------------------

Each available Form-Processor is registered by its `name` as `action` for entity `FormProcessor`

- in order to pass data to a particular Form-Processor via **Api3**, just pass its `name` as action

The names of the action can be retrieved via a Api3 call

- entity: `FormProcessor`
- action: `getactions`

Alternatively, just query the names of the available Form Processors directly via **Api4**

- entity: `FormProcessorInstance`
- action: `get`
- select: `name`

**Note:**

If a particular naming scheme is being used, on could also just select particular Form Processors
by matching to that scheme.

Data Structure for Form Processor input
---------------------------------------

Data is being passed to a Form-Processor as json formated string.

The names of the selected Form-Processor input fields are being used as json field keys.

The json is a simple structure with only `'key':'value'` pairs.

Retrieving Form Processor Imput Field Name
------------------------------------------

The names of the input fields of a Form Processor  can be retrieved via a **Api4** call

- entity: `FormProcessorInput`
- action: `get`
- select: `name`
- where: `form_processor_id = <id>`

The result is a json array that contains the names of all input field names:

```json
[
  {
    "id": 2,
    "name": "org_name"
  },
  {
    "id": 3,
    "name": "org_pic_number"
  },
  {
    "id": 4,
    "name": "org_website"
  }
]
```

Example Json structure
----------------------

FormProcessor Name:

- `eic_company_import`

Form Processor Input Fields: 

- `org_name`
- `org_pic_number`
- `org_website`

Json Data:

```bash
# the json data to be passed to the seleceted FormProcessor
paramDataJson='json={                       \
  'org_name':'api3 import company  name',   \
  'org_pic_number':'api3 import pic',       \
  'org_website':'api3 import website'       \
}'

# json needs to be encoded into

paramDataJson='%7B%22org_name%22%3A%22api3+import+company++name%22%2C%22org_pic_number%22%3A%22api3+import+pic%22%2C%22org_website%22%3A%22api3+import+website%22%7D'
```

Example Api Call
----------------

Pass data to a FormProcessor via a `curl` request to CiviCRM Api3:

Api3 Entity:

- `FormProcessor`

Entity Action:

- `eic_company_import`

Api Request:

```bash
# the FormProcess as an entity
paramEntity='entity=FormProcessor'
# use name of available form-processor as action
paramAction='action=eic_company_import'
# to be replaced with real civicrm api-key that has been create for a particular api user
paramApiKey='api_key=FIXME_USER_KEY'
# to be replaced with real civicrm size-key
paramSiteKey='key=FIXME_SITE_KEY'

# run curl
curl -X POST -d \
  "${paramEntity}&${paramAction}&${paramDataJson}&${paramApiKey}&${paramSiteKey}" \
  'http://localhost/dev_eic/libraries/civicrm/core/extern/rest.php'
 ```
