<?php
declare(strict_types = 1);

namespace Civi\EicFpActionProvider\ActionProvider\Action;

use Civi\ActionProvider\Action\AbstractAction;
use Civi\ActionProvider\Parameter\ParameterBagInterface;
use Civi\ActionProvider\Parameter\Specification;
use Civi\ActionProvider\Parameter\SpecificationBag;
use CRM_EicFpActionProvider_ExtensionUtil as E;

/**
 * Resolves a single Activity id by matching a value against one of the
 * activity's custom fields, optionally restricted to an activity type.
 *
 * This is the Activity equivalent of the built-in FindContactByCustomField
 * action: it references the custom field and activity type by NAME (not by the
 * numeric custom-field id or activity-type id), so Form Processors stay
 * portable across environments. Unlike the built-in FindSimilarActivities
 * (which returns an array), this returns a single activity id (the most recent
 * match), which can be fed directly into GetActivity or a single-value
 * EntityReference custom field.
 *
 * Configuration:
 *   custom_field   - the custom field API name in "GroupName.FieldName" form
 *                    (e.g. EIC_Horizon_Europe_Project_information.Project_Number)
 *   activity_type  - optional activity type machine name to restrict the search
 *                    (e.g. EIC_Awardee_Project)
 *
 * Input parameter:  value        - the value to match against the custom field
 * Output parameter: activity_id  - the matched activity id (empty when none)
 */
class GetActivityIdByCustomField extends AbstractAction {

  /**
   * Run the action.
   *
   * @param \Civi\ActionProvider\Parameter\ParameterBagInterface $parameters
   *   The parameters this action can access.
   * @param \Civi\ActionProvider\Parameter\ParameterBagInterface $output
   *   The output this action can set.
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output): void {
    $value = trim((string) $parameters->getParameter('value'));
    $customField = trim((string) $this->configuration->getParameter('custom_field'));
    $activityType = trim((string) $this->configuration->getParameter('activity_type'));
    $activityId = NULL;

    if ($value !== '' && $customField !== '') {
      $where = [
        [$customField, '=', $value],
        ['is_current_revision', '=', TRUE],
        ['is_deleted', '=', FALSE],
      ];
      if ($activityType !== '') {
        $where[] = ['activity_type_id:name', '=', $activityType];
      }

      $result = \civicrm_api4('Activity', 'get', [
        'select' => ['id'],
        'where' => $where,
        'orderBy' => ['activity_date_time' => 'DESC'],
        'checkPermissions' => FALSE,
        'limit' => 1,
      ]);
      $record = $result->first();
      if ($record) {
        $activityId = $record['id'];
      }
    }

    $output->setParameter('activity_id', $activityId);
  }

  /**
   * Returns the specification of the configuration options for this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag([
      new Specification('custom_field', 'String', E::ts('Custom field API name (GroupName.FieldName)'), TRUE),
      new Specification('activity_type', 'String', E::ts('Activity type machine name (optional)'), FALSE),
    ]);
  }

  /**
   * Returns the specification of the parameters of this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getParameterSpecification() {
    return new SpecificationBag([
      new Specification('value', 'String', E::ts('Value to match'), TRUE),
    ]);
  }

  /**
   * Returns the specification of the output parameters of this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getOutputSpecification() {
    return new SpecificationBag([
      new Specification('activity_id', 'Integer', E::ts('Activity id'), FALSE),
    ]);
  }

  /**
   * Returns the human readable title of this action.
   *
   * @return string
   */
  public function getTitle() {
    return E::ts('Get activity id by custom field');
  }

}
