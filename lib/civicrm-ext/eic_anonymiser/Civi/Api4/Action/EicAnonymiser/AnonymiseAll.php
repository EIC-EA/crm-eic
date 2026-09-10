<?php

namespace Civi\Api4\Action\EicAnonymiser;

use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\Result;

/**
 * Anonymise ALL EIC contacts in place (does not delete related records).
 *
 * Loops over every Individual and Organization contact and applies the same
 * in-place anonymisation as the single-contact "anonymise" action: names,
 * email, websites (incl. LinkedIn), company name and the custom fields PIC,
 * SMEDId, Company Domain Name and eulogin, plus the matching log_ tables.
 *
 * When dryRun is TRUE, nothing is written; the result reports how many
 * contacts would be processed plus a small sample of planned changes.
 *
 * A failure on one contact is recorded and does not abort the whole run.
 *
 * CAUTION: When dryRun is FALSE, this is irreversible. Take a backup first.
 *
 * @method $this setDryRun(bool $dryRun)
 * @method bool getDryRun()
 * @method $this setIncludeDeleted(bool $includeDeleted)
 * @method bool getIncludeDeleted()
 * @method $this setContactTypes(array $contactTypes)
 * @method array getContactTypes()
 * @method $this setLimit(int $limit)
 * @method int getLimit()
 * @method $this setUseSql(bool $useSql)
 * @method bool getUseSql()
 *
 * @package Civi\Api4\Action\EicAnonymiser
 */
class AnonymiseAll extends AbstractAction {

  /**
   * When TRUE, no data is written — the action only previews.
   *
   * @var bool
   */
  protected $dryRun = FALSE;

  /**
   * Whether to also process contacts in the trash (is_deleted = 1).
   *
   * @var bool
   */
  protected $includeDeleted = TRUE;

  /**
   * Which contact types to process.
   *
   * @var array
   */
  protected $contactTypes = ['Individual', 'Organization'];

  /**
   * Optional cap on the number of contacts to process (0 = no limit).
   * Useful for a staged rollout or a quick sample. Ignored in SQL mode.
   *
   * @var int
   */
  protected $limit = 0;

  /**
   * Use the fast set-based SQL path (default). Set FALSE to use the
   * per-contact loop, which is slower but returns per-contact
   * sample_changes and error detail and honours `limit`.
   *
   * @var bool
   */
  protected $useSql = TRUE;

  /**
   * @param \Civi\Api4\Generic\Result $result
   *
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result) {
    $dryRun = (bool) $this->dryRun;

    // Fast path: bulk set-based UPDATEs. `limit` is not supported here
    // because the operation is set-based; use the loop path for staged runs.
    if ($this->useSql && $this->limit <= 0) {
      $outcome = \CRM_EicAnonymiser_SqlWorker::anonymiseAll(
        $dryRun,
        (bool) $this->includeDeleted,
        $this->contactTypes
      );
      $result[] = [
        'dry_run'         => $outcome['dry_run'],
        'mode'            => 'sql',
        'total_matched'   => $outcome['total_matched'],
        'include_deleted' => $this->includeDeleted,
        'contact_types'   => $this->contactTypes,
        'updated'         => $outcome['updated'],
        'log'             => $outcome['log'],
      ];
      return;
    }

    $get = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('id')
      ->addWhere('contact_type', 'IN', $this->contactTypes)
      ->addOrderBy('id', 'ASC');

    if ($this->includeDeleted) {
      // By default APIv4 hides trashed contacts; include them explicitly.
      $get->addWhere('is_deleted', 'IN', [0, 1]);
    }

    if ($this->limit > 0) {
      $get->setLimit($this->limit);
    }

    $contactIds = (array) $get->execute()->column('id');

    $processed = 0;
    $failed = 0;
    $errors = [];
    $sample = [];

    foreach ($contactIds as $contactId) {
      $contactId = (int) $contactId;
      try {
        $outcome = \CRM_EicAnonymiser_Worker::anonymise($contactId, $dryRun);
        $processed++;
        // Keep a small sample of changes for dry-run visibility.
        if ($dryRun && count($sample) < 10 && !empty($outcome['changes'])) {
          $sample[] = [
            'contact_id' => $contactId,
            'changes'    => $outcome['changes'],
          ];
        }
      }
      catch (\Throwable $e) {
        $failed++;
        $errors[] = [
          'contact_id' => $contactId,
          'error'      => $e->getMessage(),
        ];
        \Civi::log()->error("eic_anonymiser: failed to anonymise contact {$contactId}: " . $e->getMessage());
      }
    }

    $result[] = [
      'dry_run'         => $dryRun,
      'mode'            => 'loop',
      'total_matched'   => count($contactIds),
      'processed'       => $processed,
      'failed'          => $failed,
      'include_deleted' => $this->includeDeleted,
      'contact_types'   => $this->contactTypes,
      'errors'          => $errors,
      'sample_changes'  => $sample,
    ];
  }

}
