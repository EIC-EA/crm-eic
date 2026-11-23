<?php
declare(strict_types = 1);

namespace Civi\EicConfig;

use Civi\Test;
use Civi\Test\CiviEnvBuilder;
use Civi\Test\HeadlessInterface;
use Civi\Test\TransactionalInterface;
use PHPUnit\Framework\TestCase;

// phpcs:ignore Generic.Files.LineLength.TooLong
abstract class AbstractEicConfigHeadlessTestCase extends TestCase implements HeadlessInterface, TransactionalInterface {

  public function setUpHeadless(): CiviEnvBuilder {
    return Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  protected function setUp(): void {
    parent::setUp();
    $this->setUserPermissions(['access CiviCRM']);
  }

  /**
   * @phpstan-param list<string>|null $permissions
   */
  protected function setUserPermissions(?array $permissions): void {
    $userPermissions = \CRM_Core_Config::singleton()->userPermissionClass;
    // @phpstan-ignore assign.propertyType
    $userPermissions->permissions = $permissions;
  }

}
