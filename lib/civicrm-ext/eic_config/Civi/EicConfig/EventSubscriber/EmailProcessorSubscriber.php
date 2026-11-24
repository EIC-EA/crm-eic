<?php
declare(strict_types = 1);

namespace Civi\EicConfig\EventSubscriber;

use Civi\Api4\Contact;
use Civi\Api4\Email;
use Civi\Api4\Relationship;
use Civi\Core\Service\AutoSubscriber;

final class EmailProcessorSubscriber extends AutoSubscriber {

  public static function getSubscribedEvents(): array {
    return ['&hook_civicrm_emailProcessor' => 'onEmailProcessor'];
  }

  /**
   * @param array<string, mixed> $params
   * @param array<string, mixed> $result
   *
   * @throws \CRM_Core_Exception
   */
  public function onEmailProcessor(
    string $type,
    array $params,
    object $mail,
    array $result,
    ?string $action = NULL,
    int|string|null $mailSettingId = NULL
  ): void {
    if ('activity' !== $type) {
      return;
    }

    $individualContactId = $params['source_contact_id'];
    if (!is_numeric($individualContactId)) {
      return;
    }

    $domain = $this->getPrimaryEmailAddressDomain((int) $individualContactId);
    if (NULL === $domain) {
      return;
    }

    $organizationContactId = $this->getOrganizationId($domain);
    if (NULL !== $organizationContactId) {
      Relationship::create(FALSE)
        ->addValue('contact_id_a', $individualContactId)
        ->addValue('contact_id_b', $organizationContactId)
        ->addValue('relationship_type_id:name', 'Employee of')
        ->execute();
    }
  }

  /**
   * @param string $domain
   *
   * @return int|null
   *   Contact id of organization that has an email address with the given
   *   domain. NULL, if there's no or more than one matching organization.
   *
   * @throws \CRM_Core_Exception
   */
  private function getOrganizationId(string $domain): ?int {
    $contactIds = Email::get(FALSE)
      ->addSelect('contact_id')
      ->addJoin('Contact AS contact', 'INNER', NULL,
        ['contact.id', '=', 'contact_id'],
        ['contact.contact_type', '=', '"Organization"']
      )
      ->addWhere('email', 'LIKE', '%@' . $domain)
      ->addGroupBy('contact_id')
      ->setLimit(2)
      ->execute()
      ->column('contact_id');

    return count($contactIds) === 1 ? $contactIds[0] : NULL;
  }

  private function getPrimaryEmailAddressDomain(int $contactId): ?string {
    $emailAddress = Contact::get(FALSE)
      ->addSelect('email_primary.email')
      ->addWhere('id', '=', $contactId)
      ->execute()
      ->single()['email_primary.email'];

    if (NULL === $emailAddress) {
      return NULL;
    }

    [$localPart, $domain] = explode('@', $emailAddress);

    return $domain;
  }

}
