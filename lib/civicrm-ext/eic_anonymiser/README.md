# EIC Anonymiser

A small CiviCRM companion extension that **anonymises** EIC personal and company
data **in place** — it overwrites sensitive values with deterministic,
non-identifying placeholders instead of deleting the related records.

This is a deliberate alternative to
[`de.systopia.anonymiser`](https://github.com/systopia/de.systopia.anonymiser),
which *deletes* emails, websites and related entities. Here everything is kept;
only the values change, so record counts and referential structure stay intact.

> **CAUTION:** Anonymisation is irreversible. Always run it against a copy /
> restored dump, never directly on production unless that is your explicit intent.

## What gets anonymised

| Area | Field | Storage | Result |
|---|---|---|---|
| Person | First name | `civicrm_contact.first_name` | fake name + id, e.g. `Alex-<id>` |
| Person | Last name | `civicrm_contact.last_name` | fake name + id, e.g. `Turner-<id>` |
| Person | External ID | `civicrm_contact.external_identifier` | `anon-ext-<id>` (originally held the email) |
| Person | Greetings / Addressee | `email_greeting_display`, `postal_greeting_display`, `addressee_display` (+ `_custom`) | rebuilt from the fake name |
| Both | Activity subject / details | `civicrm_activity.subject`, `civicrm_activity.details` | `Activity-<id>` / lorem-ipsum |
| Person | Email | `civicrm_email.email` | `anon-<cid>-<id>@example.invalid` |
| Person | LinkedIn / any website | `civicrm_website.url` | `https://anon-<cid>-<id>.example.invalid` |
| Person | eulogin (custom) | `EIC_Awardee_representative.eulogin` | `ANON-<cid>` |
| Company | Company name | `civicrm_contact.organization_name` | `Organization-<id>` |
| Company | Website | `civicrm_website.url` | `https://anon-<cid>-<id>.example.invalid` |
| Company | PIC (custom) | `EIC_Organisation_identifiers.PIC` | `anon-pic-<cid>` |
| Company | SMEDId (custom) | `EIC_Organisation_identifiers.SMEDId` | `anon-smedid-<cid>` |
| Company | Company Domain Name (custom) | `EIC_Organisation_identifiers.Company_Domain_Name` | `anon-<cid>.example.invalid` |
| Both | Logs / extended logs | `log_civicrm_contact`, `log_civicrm_email`, `log_civicrm_website`, and the `log_` mirror of each affected custom-value table | matching columns scrubbed |

`<id>` = the record id, `<cid>` = the contact id.

Custom-field tables and columns are resolved **at runtime** via APIv4
`CustomField` metadata (group name + field name), so the extension keeps working
even if the underlying `column_name` differs between environments. If a
configured custom field is not present, it is skipped and noted in the log.

### Empty values are left untouched

Any field whose current value is NULL or an empty string is **not** anonymised —
the extension never turns a blank into a placeholder. This applies to the base
name fields, emails, websites and the custom fields. Log-table scrubbing follows
the same rule per column: a `log_` row keeps a column as-is if that column was
already empty in that row. (The literal string `"0"` is treated as a real value,
not blank.)

## Log scrubbing

If CiviCRM logging (or extended/`fn.logging`) is enabled, each affected table
has a `log_<table>` mirror. The extension updates the same sensitive columns in
those log tables so historic rows no longer expose the original data. Rows are
**kept** (not deleted) so audit counts remain intact; only the sensitive columns
are overwritten. Log scrubbing is guarded by existence checks, so it is a no-op
when logging is not enabled.

> Note: unlike the systopia module, this extension writes to (rather than deletes
> from) log tables, so the `ARCHIVE` engine restriction does not apply.

## Dry run

Every call accepts a `dryRun` (APIv4) / `dry_run` (APIv3) flag. When set, the
extension **writes nothing**. Instead it reads the current values and returns a
`changes` array showing, per field, the current value and the value it *would*
write, plus how many `log_` rows it *would* scrub. This is the safe way to
preview before committing — ideal inside a container against a restored dump.

```bash
# Preview, no writes
cv api4 EicAnonymiser.anonymise '{"contactId": 1234, "dryRun": true}'
```

The `changes` entries look like:

```json
{ "target": "Contact[1234]", "field": "last_name", "from": "Rossi", "to": "Last-1234" }
```

## Usage

### APIv4 (recommended)

```php
// PHP — real run
\Civi\Api4\EicAnonymiser::anonymise(FALSE)
  ->setContactId(1234)
  ->execute();

// PHP — dry run
\Civi\Api4\EicAnonymiser::anonymise(FALSE)
  ->setContactId(1234)
  ->setDryRun(TRUE)
  ->execute();
```

```bash
# cv — real run
cv api4 EicAnonymiser.anonymise '{"contactId": 1234}'
# cv — dry run
cv api4 EicAnonymiser.anonymise '{"contactId": 1234, "dryRun": true}'
```

### APIv3

```bash
cv api3 EicAnonymiser.Anonymise contact_id=1234            # real run
cv api3 EicAnonymiser.Anonymise contact_id=1234 dry_run=1  # dry run
```

```php
civicrm_api3('EicAnonymiser', 'Anonymise', ['contact_id' => 1234, 'dry_run' => 1]);
```

### Bulk — whole database (`anonymiseAll`)

To anonymise the entire database in one call, use the `anonymiseAll` action. It
loops over every Individual and Organization contact (including trashed ones by
default) and applies the same in-place anonymisation as the single-contact
action. A failure on one contact is recorded and does not abort the run.

```bash
# Preview the whole DB — writes nothing, returns per-table row counts
cv api4 EicAnonymiser.anonymiseAll '{"dryRun": true}'

# Real run — irreversible, take a backup first
cv api4 EicAnonymiser.anonymiseAll '{}'
```

By default this uses a **fast set-based SQL path**: a handful of `UPDATE`
statements touch every matching row at once, instead of looping one contact at
a time. On a ~45k-contact database this is the difference between seconds and
~20 minutes. The anonymised values are identical to the per-contact action.

Options:

| Param | Default | Meaning |
|---|---|---|
| `dryRun` | `false` | Preview only, no writes |
| `includeDeleted` | `true` | Also process contacts in the trash |
| `contactTypes` | `["Individual","Organization"]` | Which types to process |
| `useSql` | `true` | Fast set-based SQL path. Set `false` for the per-contact loop |
| `limit` | `0` (all) | Cap the number of contacts. Forces the loop path (SQL mode is set-based) |

**SQL mode vs loop mode.** SQL mode (`useSql: true`, the default) is fast but
returns per-table affected-row counts rather than per-contact diffs. The loop
mode (`useSql: false`, or any run with `limit`) is slower but returns
`sample_changes` and per-contact `errors`, which is handy for spot-checking.
Both produce identical anonymised values, so use the loop for a small dry-run
preview and SQL for the actual full run.

The result summary in SQL mode reports rows touched per table:

```json
{
  "dry_run": false,
  "mode": "sql",
  "total_matched": 44694,
  "include_deleted": true,
  "contact_types": ["Individual", "Organization"],
  "updated": {
    "civicrm_contact": 44694,
    "civicrm_email": 40001,
    "civicrm_website": 12000,
    "civicrm_value_srm_org_ids": 5000,
    "civicrm_value_srm_eic_awardee_representative": 30000,
    "log_civicrm_contact": 120000,
    "log_civicrm_email": 41000,
    "log_civicrm_website": 12500
  },
  "log": ["44694 contact(s) in scope.", "Updated ... row(s) in ...", "..."]
}
```

In loop mode (`useSql: false` or with `limit`) the summary instead reports
`processed`, `failed`, `errors` and `sample_changes`.

Staged example — do 100 first, check, then the rest:

```bash
cv api4 EicAnonymiser.anonymiseAll '{"limit": 100}'
cv api4 EicAnonymiser.anonymiseAll '{}'   # remaining (already-anonymised ones are cheap re-runs)
```

Notes:
- Re-running is safe: an already-anonymised field either matches the placeholder
  or is skipped when blank, so a second pass won't corrupt data.
- For very large databases, looping the API is slower than raw SQL. If you need
  a fast one-shot scrub of a fresh dump, a direct SQL script against the same
  tables/columns is the faster route.

### Bulk — a specific selection

To anonymise only certain contacts, loop the single-contact action over ids:

```bash
cv api4 Contact.get '{"select":["id"],"where":[["contact_type","IN",["Individual","Organization"]]],"limit":0}' \
  | php -r '$r=json_decode(stream_get_contents(STDIN),true); foreach($r as $c){echo $c["id"],"\n";}' \
  | while read id; do cv api4 EicAnonymiser.anonymise "{\"contactId\": $id}"; done
```

## Permissions

- `anonymise` requires **administer CiviCRM**.
- `getFields` requires **access CiviCRM**.

## Return value

Each call returns one row:

```json
{
  "contact_id": 1234,
  "dry_run": false,
  "anonymised": true,
  "changes": [
    { "target": "Contact[1234]", "field": "last_name", "from": "Rossi", "to": "Last-1234" }
  ],
  "log": ["Anonymised base name for Individual contact 1234.", "..."]
}
```

In dry-run mode `dry_run` is `true`, `anonymised` is `false`, and `changes`
holds the preview.

The `log` array is also written to `Civi::log()` under the `eic_anonymiser`
prefix.

## Relationship to de.systopia.anonymiser

This extension is fully independent — it does not require, extend or patch the
systopia module. If you also run the systopia anonymiser for its statistical
handling of contributions/memberships/participants, run this one **first** (or
instead), because the systopia module deletes the Email/Website records this
extension anonymises.
