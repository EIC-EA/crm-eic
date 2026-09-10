<?php

use CRM_EicAnonymiser_ExtensionUtil as E;

/**
 * Safety guard for the anonymiser.
 *
 * Anonymisation is irreversible, so it must only run in environments that
 * have explicitly opted in via the "allow_anonymization" environment variable.
 * This prevents an accidental run against production.
 */
class CRM_EicAnonymiser_Guard {

  /**
   * Throw unless anonymisation is explicitly allowed in this environment.
   *
   * The env var "allow_anonymization" must be set to a truthy value
   * (true / 1 / yes / on, case-insensitive). If it is missing or set to
   * anything else, the operation is refused.
   *
   * Dry-run is exempt: it writes nothing, so previewing is always permitted.
   *
   * @param bool $dryRun
   * @throws \CRM_Core_Exception
   */
  public static function assertAllowed(bool $dryRun = FALSE): void {
    if ($dryRun) {
      return;
    }
    if (!self::isAllowed()) {
      throw new CRM_Core_Exception(E::ts(
        'Anonymisation is disabled: the "allow_anonymization" environment '
        . 'variable is not set to a truthy value (true/1/yes/on). Refusing to '
        . 'modify data. Set allow_anonymization=true to enable, or use dryRun '
        . 'to preview without writing.'
      ));
    }
  }

  /**
   * @return bool TRUE if the env var opts in to anonymisation.
   */
  public static function isAllowed(): bool {
    $value = getenv('allow_anonymization');
    if ($value === FALSE) {
      return FALSE;
    }
    $normalized = strtolower(trim((string) $value));
    return in_array($normalized, ['true', '1', 'yes', 'on'], TRUE);
  }

}
