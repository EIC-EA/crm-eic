# CiviCRM CSV Importer

A CLI PHP application that imports CSV data into CiviCRM using **APIv4**,
run via `cv php:script`.


# HOWTO
    cd /opt/drupal/vendor/civicrm/civicrm-core/ext/eic_import
    cv --verbose --user=admin php:script import.php -- organisations --file=data/csv/organisation_sample.csv
    cv --verbose --user=admin php:script import.php -- organisations --file=data/csv/organisation_sample.csv --update-existing
    cv --verbose --user=admin php:script import.php -- persons --file=data/csv/person_sample.csv

---

## Why `cv php:script`?

`cv php:script` bootstraps CiviCRM automatically before executing the script.
This means:

- No `civicrm.settings.php` path configuration needed
- All `\Civi\Api4\*` and `CRM_*` classes are available immediately
- The correct database connection is already established
- `cv` resolves the CiviCRM root from the directory you run it in
  (or via `--cwd` / `CV_ROOT`)

---

## Directory Structure

```
civicrm-csv-import/
├── import.php                        ← cv php:script entry point
├── sample_organisations.csv          ← Example CSV
└── src/
    ├── Config/
    │   └── FieldMap.php              ← CSV column → CiviCRM field mapping  ← EDIT THIS
    ├── Parser/
    │   └── CsvParser.php             ← CSV reader (generator-based)
    ├── Logger/
    │   ├── ImportLogger.php          ← Timestamped logging to stdout + file
    │   └── ImportStats.php           ← Counters and per-row error collection
    └── Importer/
        ├── BaseImporter.php          ← Shared validation, bucketing, helpers
        └── OrganisationImporter.php  ← Organisation-specific import logic
```

---

## CSV Format

The organisations CSV uses a **semicolon** delimiter with this header row:

```
PIC;legalName;streetNameAndNumber;postalCode;city;country;website;program
```

| Column                | CiviCRM Target                             | Notes                        |
|-----------------------|--------------------------------------------|------------------------------|
| `PIC`                 | Custom field `OrganisationDetails.pic`     | Dedupe key — must be unique  |
| `legalName`           | `Contact.organization_name`               | Required                     |
| `streetNameAndNumber` | `Address.street_address`                  |                              |
| `postalCode`          | `Address.postal_code`                     |                              |
| `city`                | `Address.city`                            |                              |
| `country`             | `Address.country_id:label`                | Resolved by name via APIv4   |
| `website`             | `Website.url`                             | `https://` prepended if missing |
| `program`             | Custom field `OrganisationDetails.program` |                             |

---

## Setup

### 1. Configure Custom Field Names

Edit `src/Config/FieldMap.php` and update the two custom field dot-notation
references to match your CiviCRM **custom group name** and **field name**
(not the label — the machine name):

```php
// PIC
'field' => 'OrganisationDetails.pic',

// Program
'field' => 'OrganisationDetails.program',
```

Find these names at:
**CiviCRM → Administer → Custom Data → (your group) → Fields → Name column**

### 2. Place the CSV file

Put your CSV file anywhere accessible on the server.
Paths can be absolute or relative to the directory where you run `cv`.

---

## Usage

Run all commands from the CiviCRM root (where `cv` can detect your site),
or pass `--cwd=/path/to/site` to `cv`.

```bash
# Dry run — validates headers and rows, no data written
cv php:script import.php organisations --file=sample_organisations.csv --dry-run

# Full import — create new organisations
cv php:script import.php organisations --file=sample_organisations.csv

# Update existing organisations (matched by PIC) instead of skipping
cv php:script import.php organisations --file=sample_organisations.csv --update-existing

# Comma-delimited file
cv php:script import.php organisations --file=orgs.csv --delimiter=","

# Skip 1 leading row before the header (e.g. a title row)
cv php:script import.php organisations --file=orgs.csv --skip-rows=1

# Write log output to a file as well as stdout
cv php:script import.php organisations --file=orgs.csv --log=/var/log/civicrm-import.log

# Combine flags
cv php:script import.php organisations --file=orgs.csv --dry-run --log=dry-run.log

# Show help
cv php:script import.php --help

# Verbose API/debug output
IMPORT_DEBUG=1 cv php:script import.php organisations --file=orgs.csv
```

### Running as a different CiviCRM user

```bash
cv --user=admin php:script import.php organisations --file=orgs.csv
```

### Specifying the site root explicitly

```bash
cv --cwd=/var/www/html php:script import.php organisations --file=orgs.csv
```

---

## How Deduplication Works

Each organisation is deduplicated by its **PIC** custom field value.

Before creating a contact the importer queries CiviCRM for an existing
Organisation with the same PIC. If found:

| Flag                  | Behaviour                                   |
|-----------------------|---------------------------------------------|
| *(default)*           | Row is skipped, counted as **Skipped**      |
| `--update-existing`   | Contact, Address, and Website are updated   |

---

## Output

```
[2026-05-08 14:32:01] [INFO ] ────────────────────────────────────────────────────────────
[2026-05-08 14:32:01] [INFO ] Organisation Import
[2026-05-08 14:32:01] [INFO ] ────────────────────────────────────────────────────────────
[2026-05-08 14:32:01] [INFO ] File      : sample_organisations.csv
[2026-05-08 14:32:01] [INFO ] Delimiter : ;
[2026-05-08 14:32:01] [INFO ] Dry run   : NO
[2026-05-08 14:32:01] [INFO ] On match  : Skip
[2026-05-08 14:32:01] [INFO ] Headers validated. Columns: PIC, legalName, ...
[2026-05-08 14:32:01] [INFO ] Line 2     | PIC: IE123456789     | Acme Technologies Ltd
[2026-05-08 14:32:01] [INFO ]   → Created (ID 1042)
[2026-05-08 14:32:02] [INFO ] Line 3     | PIC: FR987654321     | Société Innovante SARL
[2026-05-08 14:32:02] [INFO ]   → Created (ID 1043)
...
[2026-05-08 14:32:05] [INFO ] ────────────────────────────────────────────────────────────
[2026-05-08 14:32:05] [INFO ] Import Summary
[2026-05-08 14:32:05] [INFO ] ────────────────────────────────────────────────────────────
[2026-05-08 14:32:05] [INFO ]   Total rows processed : 7
[2026-05-08 14:32:05] [INFO ]   Created              : 6
[2026-05-08 14:32:05] [INFO ]   Updated              : 0
[2026-05-08 14:32:05] [INFO ]   Skipped (duplicate)  : 0
[2026-05-08 14:32:05] [INFO ]   Failed               : 1
[2026-05-08 14:32:05] [ERROR] Rows that failed:
[2026-05-08 14:32:05] [ERROR]   Line 7     | PIC: PL998877665        | Required field 'legalName' is empty.
```

---

## Exit Codes

| Code | Meaning                                               |
|------|-------------------------------------------------------|
| `0`  | All rows processed successfully                       |
| `1`  | One or more rows failed (cv returns this to the shell)|
| `2`  | Fatal error (bad file, missing headers, API failure)  |

---

## Extending for Additional CSV Types

To add a new CSV type (e.g. `individuals`):

1. Add `FieldMap::individuals(): array` in `src/Config/FieldMap.php`
2. Create `src/Importer/IndividualImporter.php` extending `BaseImporter`
3. Register it in `import.php`:

```php
// In the $validCommands array:
$validCommands = ['organisations', 'individuals'];

// In the match expression:
match ($command) {
    'organisations' => (new OrganisationImporter($config, $logger, $stats))->run(),
    'individuals'   => (new IndividualImporter($config, $logger, $stats))->run(),
};
```

Then run:

```bash
cd /opt/drupal/vendor/civicrm/civicrm-core/ext/eic_import
cv php:script import.php organisations -- --file=data/csv/organisation_sample.csv
```



# CiviCRM CSV Importer

A CLI PHP application that imports CSV data into CiviCRM using **APIv4**.

---

## Directory Structure

```
civicrm-csv-import/
├── import.php                        ← CLI entry point
├── bootstrap.php                     ← CiviCRM bootstrap
├── sample_organisations.csv          ← Example CSV
└── src/
    ├── Config/
    │   └── FieldMap.php              ← CSV column → CiviCRM field mapping
    ├── Parser/
    │   └── CsvParser.php             ← CSV reader / iterator
    ├── Logger/
    │   ├── ImportLogger.php          ← Timestamped logging to stdout + file
    │   └── ImportStats.php           ← Counters and error collection
    └── Importer/
        ├── BaseImporter.php          ← Shared validation, bucketing, helpers
        └── OrganisationImporter.php  ← Organisation-specific import logic
```

---

## CSV Format

The organisations CSV uses a **semicolon** delimiter with this header row:

```
PIC;legalName;streetNameAndNumber;postalCode;city;country;website;program
```

| Column               | CiviCRM Target                            | Notes                        |
|----------------------|-------------------------------------------|------------------------------|
| `PIC`                | Custom field `OrganisationDetails.pic`    | Dedupe key — must be unique  |
| `legalName`          | `Contact.organization_name`              | Required                     |
| `streetNameAndNumber`| `Address.street_address`                 |                              |
| `postalCode`         | `Address.postal_code`                    |                              |
| `city`               | `Address.city`                           |                              |
| `country`            | `Address.country_id:label`               | Resolved by country name     |
| `website`            | `Website.url`                            | `https://` added if missing  |
| `program`            | Custom field `OrganisationDetails.program`|                             |

---

## Setup

### 1. Bootstrap CiviCRM

The script auto-detects Drupal, WordPress, and Joomla paths. For non-standard
installations, set an environment variable:

```bash
export CIVICRM_SETTINGS_PATH=/opt/drupal/web/sites/default/civicrm.settings.php
```

### 2. Configure Custom Field Names

Edit `src/Config/FieldMap.php` and update the two custom field references to
match the **Name** (not label) of your CiviCRM custom group and fields:

```php
// PIC field
'field' => 'OrganisationDetails.pic',

// Program field
'field' => 'OrganisationDetails.program',
```

Find these names under:
**CiviCRM Admin → Custom Data → (your group) → Fields → Name column**

---

## Usage

```bash
# Basic import
cd /opt/drupal/vendor/civicrm/civicrm-core/ext/eic_import
export CIVICRM_DRUPAL_ROOT=/opt/drupal/web
php import.php organisations --file=data/csv/organisation_sample.csv --delimiter=";"


# Dry run — no data written, full logging output
php import.php organisations --file=sample_organisations.csv --dry-run

# Update existing contacts (matched by PIC) instead of skipping them
php import.php organisations --file=sample_organisations.csv --update-existing

# Custom delimiter (e.g. comma-delimited)
php import.php organisations --file=orgs.csv --delimiter=","

# Write log to a file
php import.php organisations --file=orgs.csv --log=import.log

# Combine flags
php import.php organisations --file=orgs.csv --dry-run --log=dry-run.log

# Debug mode — verbose API output
IMPORT_DEBUG=1 php import.php organisations --file=orgs.csv
```

---

## How Deduplication Works

Each organisation is deduplicated by its **PIC** value (the custom field).

Before creating a new contact, the importer queries CiviCRM for an existing
Organisation with the same PIC. If found:

- **Default (`--update-existing` not set):** Row is skipped and counted as "Skipped".
- **With `--update-existing`:** Contact, Address, and Website are updated in place.

---

## Exit Codes

| Code | Meaning                                      |
|------|----------------------------------------------|
| `0`  | All rows processed successfully              |
| `1`  | One or more rows failed                      |
| `2`  | Fatal error (bad file path, CiviCRM boot failure, etc.) |

Useful for cron monitoring or CI pipelines.

---

## Adding More CSV Types

To add a new CSV type (e.g. `individuals`):

1. Add a new entry in `src/Config/FieldMap.php`:
   ```php
   public static function individuals(): array { ... }
   ```

2. Create `src/Importer/IndividualImporter.php` extending `BaseImporter`.

3. Register the command in `import.php`:
   ```php
   match ($command) {
       'organisations' => (new OrganisationImporter(...))->run(),
       'individuals'   => (new IndividualImporter(...))->run(),
   };
   ```

4. Add `individuals` to the `$validCommands` array.
