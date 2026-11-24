<?php
declare(strict_types = 1);

namespace Civi\EicConfig\EventSubscriber;

use Civi\Api4\Contact;
use Civi\Api4\Relationship;
use Civi\EicConfig\AbstractEicConfigHeadlessTestCase;

/**
 * @covers \Civi\EicConfig\EventSubscriber\EmailProcessorSubscriber
 *
 * @group headless
 */
final class EmailProcessorSubscriberTest extends AbstractEicConfigHeadlessTestCase {

  private EmailProcessorSubscriber $subscriber;

  protected function setUp(): void {
    parent::setUp();
    $this->subscriber = new EmailProcessorSubscriber();
  }

  public function testGetSubscribedEvents(): void {
    $expectedSubscribedEvents = ['&hook_civicrm_emailProcessor' => 'onEmailProcessor'];
    static::assertEquals($expectedSubscribedEvents, $this->subscriber::getSubscribedEvents());

    foreach ($expectedSubscribedEvents as $method) {
      static::assertTrue(method_exists(get_class($this->subscriber), $method));
    }
  }

  public function testOnEmailProcessor(): void {
    $individual = Contact::create(FALSE)
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'Foo',
        'last_name' => 'Bar',
        'email_primary.email' => 'foo@bar.org',
      ])
      ->execute()
      ->single();

    $organization = Contact::create(FALSE)
      ->setValues([
        'contact_type' => 'Organization',
        'organization_name' => 'Bar Org',
        'email_primary.email' => 'info@bar.org',
      ])
      ->execute()
      ->single();

    $this->subscriber->onEmailProcessor('activity', ['source_contact_id' => $individual['id']], new \ezcMail(), []);

    static::assertSame(1, Relationship::get(FALSE)
      ->selectRowCount()
      ->addWhere('contact_id_a', '=', $individual['id'])
      ->addWhere('contact_id_b', '=', $organization['id'])
      ->addWhere('relationship_type_id:name', '=', 'Employee of')
      ->execute()
      ->countMatched()
    );
  }

  public function testOnEmailProcessor_NoMatchingOrganization(): void {
    $individual = Contact::create(FALSE)
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'Foo',
        'last_name' => 'Bar',
        'email_primary.email' => 'foo@bar.org',
      ])
      ->execute()
      ->single();

    Contact::create(FALSE)
      ->setValues([
        'contact_type' => 'Organization',
        'organization_name' => 'Bar Org',
        'email_primary.email' => 'info@bar.com',
      ])
      ->execute()
      ->single();

    $this->subscriber->onEmailProcessor('activity', ['source_contact_id' => $individual['id']], new \ezcMail(), []);

    static::assertSame(0, Relationship::get(FALSE)
      ->selectRowCount()
      ->execute()
      ->countMatched()
    );
  }

  public function testOnEmailProcessor_TwoMatchingOrganizations(): void {
    $individual = Contact::create(FALSE)
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'Foo',
        'last_name' => 'Bar',
        'email_primary.email' => 'foo@bar.org',
      ])
      ->execute()
      ->single();

    Contact::create(FALSE)
      ->setValues([
        'contact_type' => 'Organization',
        'organization_name' => 'Bar1 Org',
        'email_primary.email' => 'info1@bar.org',
      ])
      ->execute()
      ->single();

    Contact::create(FALSE)
      ->setValues([
        'contact_type' => 'Organization',
        'organization_name' => 'Bar2 Org',
        'email_primary.email' => 'info2@bar.org',
      ])
      ->execute()
      ->single();

    $this->subscriber->onEmailProcessor('activity', ['source_contact_id' => $individual['id']], new \ezcMail(), []);

    static::assertSame(0, Relationship::get(FALSE)
      ->selectRowCount()
      ->execute()
      ->countMatched()
    );
  }

}
