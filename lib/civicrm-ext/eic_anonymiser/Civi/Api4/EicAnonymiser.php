<?php

namespace Civi\Api4;

use Civi\Api4\Generic\AbstractEntity;
use Civi\Api4\Generic\BasicGetFieldsAction;

/**
 * EicAnonymiser entity.
 *
 * Provides the "Anonymise" action which anonymises an EIC contact in place
 * (names, email, websites incl. LinkedIn, company name, and the custom fields
 * PIC, SMEDId, Company Domain Name, eulogin) and scrubs the matching log_
 * tables. Related records are kept, not deleted.
 *
 * @searchable none
 * @package Civi\Api4
 */
class EicAnonymiser extends AbstractEntity {

  /**
   * @param bool $checkPermissions
   * @return \Civi\Api4\Action\EicAnonymiser\Anonymise
   */
  public static function anonymise($checkPermissions = TRUE) {
    return (new \Civi\Api4\Action\EicAnonymiser\Anonymise(static::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return \Civi\Api4\Action\EicAnonymiser\AnonymiseAll
   */
  public static function anonymiseAll($checkPermissions = TRUE) {
    return (new \Civi\Api4\Action\EicAnonymiser\AnonymiseAll(static::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @param bool $checkPermissions
   * @return \Civi\Api4\Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    return (new BasicGetFieldsAction(static::getEntityName(), __FUNCTION__, function () {
      return [
        [
          'name'  => 'contact_id',
          'title' => 'Contact ID',
          'data_type' => 'Integer',
          'required'  => TRUE,
        ],
        [
          'name'  => 'dry_run',
          'title' => 'Dry Run',
          'data_type' => 'Boolean',
          'required'  => FALSE,
          'default_value' => FALSE,
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

  /**
   * @return array
   */
  public static function permissions() {
    return [
      'anonymise'     => ['administer CiviCRM'],
      'anonymiseAll'  => ['administer CiviCRM'],
      'getFields'     => ['access CiviCRM'],
    ];
  }

}
