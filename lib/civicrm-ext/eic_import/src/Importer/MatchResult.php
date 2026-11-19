<?php
/**
 * src/Importer/MatchResult.php
 *
 * Value object describing the outcome of BaseImporter::findByPK().
 *
 * Replaces the old implicit "array | bool" convention, where the base class
 * had to sniff the return value (false = skip; in_array(null, $ids) = create;
 * otherwise = update). That convention broke down for importers whose row maps
 * to several sub-entities (e.g. ContactImporter: a Relationship plus an
 * ActivityContact), because a legitimately-absent secondary id (null) forced
 * the whole row to be treated as "create".
 *
 * A MatchResult makes the intent explicit:
 *   - UNPROCESSABLE : the row should be skipped without touching CiviCRM
 *                     (dry-run, blank PK, or a business rule says "ignore").
 *   - NOT_FOUND     : nothing exists yet -> create.
 *   - FOUND         : the entity (and all its sub-entities) already exist
 *                     -> update or skip, depending on --update-existing.
 *   - PARTIAL       : the main entity exists but one or more sub-entities are
 *                     missing -> upsert (updateEntity fills the gaps via its
 *                     own per-sub-entity guards).
 *
 * `ids` carries the importer-specific id map (same keys the importer's
 * createEntity()/updateEntity() already read, e.g. 'id', 'project_activity_id',
 * 'relationship_id', 'activity_contact_id'). The base class never interprets
 * the keys; it only passes the map back to updateEntity().
 */

declare(strict_types=1);

final class MatchResult
{
    public const UNPROCESSABLE = 'unprocessable';
    public const NOT_FOUND     = 'not_found';
    public const FOUND         = 'found';
    public const PARTIAL       = 'partial';

    /**
     * @param string               $status One of the class constants.
     * @param array<string, ?int>  $ids    Importer-specific id map.
     */
    private function __construct(
        public readonly string $status,
        public readonly array $ids = [],
    ) {
    }

    /** The row must not be processed (dry-run / blank PK / business skip). */
    public static function unprocessable(): self
    {
        return new self(self::UNPROCESSABLE);
    }

    /** Nothing exists yet — the row will be created. */
    public static function notFound(array $ids = []): self
    {
        return new self(self::NOT_FOUND, $ids);
    }

    /** Everything already exists — the row will be updated or skipped. */
    public static function found(array $ids): self
    {
        return new self(self::FOUND, $ids);
    }

    /** The main entity exists but some sub-entities are missing — upsert. */
    public static function partial(array $ids): self
    {
        return new self(self::PARTIAL, $ids);
    }

    public function isUnprocessable(): bool
    {
        return $this->status === self::UNPROCESSABLE;
    }

    public function exists(): bool
    {
        return $this->status === self::FOUND || $this->status === self::PARTIAL;
    }

    /**
     * Translate the legacy findByPK() return (array | bool) into a MatchResult,
     * so importers can be migrated one at a time without breaking the base class.
     *
     *   false            -> UNPROCESSABLE
     *   array, no nulls  -> FOUND
     *   array, all null  -> NOT_FOUND
     *   array, some null -> PARTIAL
     *
     * @param array<string, ?int>|bool $legacy
     */
    public static function fromLegacy(array|bool $legacy): self
    {
        if ($legacy === false) {
            return self::unprocessable();
        }

        $values   = array_values($legacy);
        $nullCount = 0;
        foreach ($values as $v) {
            if ($v === null) {
                $nullCount++;
            }
        }

        if ($nullCount === 0) {
            return self::found($legacy);
        }
        if ($nullCount === count($values)) {
            return self::notFound($legacy);
        }
        return self::partial($legacy);
    }
}
