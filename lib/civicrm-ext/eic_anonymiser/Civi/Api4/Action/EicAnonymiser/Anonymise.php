<?php

namespace Civi\Api4\Action\EicAnonymiser;

use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\Result;

/**
 * Anonymise an EIC contact in place (does not delete related records).
 *
 * Overwrites names, email, websites (incl. LinkedIn), company name and the
 * custom fields PIC, SMEDId, Company Domain Name and eulogin with
 * deterministic, non-identifying placeholders, then scrubs the matching
 * log_ tables.
 *
 * When dryRun is TRUE, nothing is written; the result reports the current
 * value and the value that would be written for each field.
 *
 * CAUTION: When dryRun is FALSE, this is irreversible.
 *
 * @method $this setContactId(int $contactId)
 * @method int|null getContactId()
 * @method $this setDryRun(bool $dryRun)
 * @method bool getDryRun()
 *
 * @package Civi\Api4\Action\EicAnonymiser
 */
class Anonymise extends AbstractAction {

  /**
   * The ID of the contact to anonymise.
   *
   * @var int
   * @required
   */
  protected $contactId;

  /**
   * When TRUE, no data is written — the action only previews the changes.
   *
   * @var bool
   */
  protected $dryRun = FALSE;

  /**
   * @param \Civi\Api4\Generic\Result $result
   *
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result) {
    $contactId = (int) $this->contactId;
    $outcome = \CRM_EicAnonymiser_Worker::anonymise($contactId, (bool) $this->dryRun);

    $result[] = [
      'contact_id' => $contactId,
      'dry_run'    => $outcome['dry_run'],
      'anonymised' => !$outcome['dry_run'],
      'changes'    => $outcome['changes'],
      'log'        => $outcome['log'],
    ];
  }

}
