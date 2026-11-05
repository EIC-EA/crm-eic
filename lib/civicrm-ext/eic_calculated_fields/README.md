# EIC Calculated Fields

CiviCRM extension that keeps derived ("calculated") custom fields in sync when
an entity is saved.

## Current behaviour

**VentureMatch Readiness Score.** When an EIC Awardee organisation is saved, the
`Readiness Score` custom field is recomputed as:

```
Readiness Score = Pitch Deck + C-Level/Team + Data Room + Financial Model
```

Any component that is unset / empty counts as `0`, so a score is always written.

### Fields

All fields belong to the `VentureMatch_Fields` custom group (table
`civicrm_value_srm_eic_venturematch`), provided by the `eic_config` extension
and attached to Organization contacts of subtype `EIC_Awardee`.

| Role      | Label            | Field name          |
|-----------|------------------|---------------------|
| component | Pitch Deck       | `Pitch_Deck2`       |
| component | C-Level/Team     | `C_Level_Team2`     |
| component | Data Room        | `Data_Room2`        |
| component | Financial Model  | `Financial_Model2`  |
| target    | Readiness Score  | `Readiness_Score`   |

The target field is flagged `is_view` (read-only in the form UI) and is set
programmatically by this extension.

## How it works

The trigger is `hook_civicrm_custom`, which CiviCRM fires once per custom group
whose values are written during a save (any save path: full contact form,
inline/quick edit, API4, or import). The implementation:

1. Ignores operations other than `create` / `edit`.
2. Reacts only when the written group is `VentureMatch_Fields`.
3. Reads the full, persisted record for the four component fields via API4 —
   not the hook's `$params`, which only contains the fields that changed in that
   particular save.
4. Sums the components (unset = 0) and writes the total back to
   `Readiness_Score` via API4, but only if it differs from the stored value.

A static reentrancy guard in the hook prevents the write-back (which itself
touches the same custom group) from recursing.

## Dependencies

- CiviCRM 6.14+
- The `VentureMatch_Fields` custom group (from `eic_config`). If the group is
  absent the extension no-ops safely.

## Extending

Additional calculated fields can be added as further `Civi\EicCalculatedFields\*`
helpers invoked from the relevant hook; the Readiness Score logic lives in
`Civi/EicCalculatedFields/ReadinessScore.php`.
