<?php
declare(strict_types = 1);

namespace Civi\EicFpActionProvider\ActionProvider\Action;

use Civi\ActionProvider\Action\AbstractAction;
use Civi\ActionProvider\ConfigContainer;
use Civi\ActionProvider\Exception\ExecutionException;
use Civi\ActionProvider\Parameter\ParameterBagInterface;
use Civi\ActionProvider\Parameter\Specification;
use Civi\ActionProvider\Parameter\SpecificationBag;
use Civi\ActionProvider\Utils\CustomField;
use CRM_EicFpActionProvider_ExtensionUtil as E;

/**
 * Updates ONLY the custom field values of an existing Activity, identified by
 * its id. Nothing else on the activity is touched.
 *
 * This is the Activity equivalent of the built-in CaseUpdateCustomData action.
 * It exists because the built-in CreateActivity action cannot be used to patch
 * custom data on an existing activity: CreateActivity treats source_contact_id
 * and target_contact_id as required and passes them through (blanking them on an
 * update), and it only accepts a single fixed status from configuration - so it
 * either fails ("Could not create activity") or clobbers the activity's source,
 * target and status. This action calls Activity.create with just the id and the
 * mapped custom fields, leaving everything else intact.
 *
 * Input parameters:
 *   activity_id              - the activity to update (required)
 *   custom_<group>_<field>   - one per active Activity custom field
 */
class ActivityUpdateCustomData extends AbstractAction {

  /**
   * Run the action.
   *
   * @param \Civi\ActionProvider\Parameter\ParameterBagInterface $parameters
   *   The parameters to this action.
   * @param \Civi\ActionProvider\Parameter\ParameterBagInterface $output
   *   The parameters this action can send back.
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output): void {
    $activityId = $parameters->getParameter('activity_id');
    if (empty($activityId)) {
      return;
    }

    $apiParams = ['id' => $activityId];
    $apiParams = array_merge($apiParams, CustomField::getCustomFieldsApiParameter($parameters, $this->getParameterSpecification()));

    try {
      civicrm_api3('Activity', 'create', $apiParams);
    }
    catch (\Exception | \CRM_Core_Exception | \TypeError $e) {
      throw new ExecutionException('Could not update activity custom data: ' . $e->getMessage());
    }
  }

  /**
   * Returns the specification of the configuration options for this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag();
  }

  /**
   * Returns the specification of the parameters of this action.
   *
   * The custom field parameters are built dynamically from every active
   * Activity custom group, exactly like CaseUpdateCustomData does for cases.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getParameterSpecification() {
    $specs = new SpecificationBag([
      new Specification('activity_id', 'Integer', E::ts('Activity ID'), TRUE),
    ]);

    $config = ConfigContainer::getInstance();
    $customGroups = $config->getCustomGroupsForEntity('Activity');
    foreach ($customGroups as $customGroup) {
      if (!empty($customGroup['is_active'])) {
        $specs->addSpecification(CustomField::getSpecForCustomGroup($customGroup['id'], $customGroup['name'], $customGroup['title']));
      }
    }

    return $specs;
  }

  /**
   * Returns the specification of the output parameters of this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getOutputSpecification() {
    return new SpecificationBag();
  }

  /**
   * Returns the human readable title of this action.
   *
   * @return string
   */
  public function getTitle() {
    return E::ts('Update activity custom data');
  }

}
