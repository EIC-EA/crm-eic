<?php
declare(strict_types = 1);

namespace Civi\EicFpActionProvider\ActionProvider\Action;

use Civi\ActionProvider\Action\AbstractAction;
use Civi\ActionProvider\Parameter\ParameterBagInterface;
use Civi\ActionProvider\Parameter\Specification;
use Civi\ActionProvider\Parameter\SpecificationBag;
use CRM_EicFpActionProvider_ExtensionUtil as E;

/**
 * Resolves a CiviCRM CaseType id from its machine name (or, as a fallback,
 * its title).
 *
 * This mirrors the built-in GetRelationshipTypeIdByName action and lets
 * Form Processors reference case types by name instead of hardcoding the
 * numeric id, which is not portable across environments (the id is assigned
 * by the database on install).
 *
 * Input parameter:  name  - the CaseType machine name (e.g. eic_awardee_onboarding)
 * Output parameter: id    - the numeric CaseType id (empty when not found)
 */
class GetCaseTypeIdByName extends AbstractAction {

  /**
   * Run the action.
   *
   * @param \Civi\ActionProvider\Parameter\ParameterBagInterface $parameters
   *   The parameters this action can access.
   * @param \Civi\ActionProvider\Parameter\ParameterBagInterface $output
   *   The output this action can set.
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output): void {
    $name = trim((string) $parameters->getParameter('name'));
    $id = NULL;

    if ($name !== '') {
      // First try to match on the machine name.
      $result = \civicrm_api4('CaseType', 'get', [
        'select' => ['id'],
        'where' => [['name', '=', $name]],
        'checkPermissions' => FALSE,
        'limit' => 1,
      ]);
      $record = $result->first();

      // Fallback: match on the title, in case a title was provided.
      if (!$record) {
        $result = \civicrm_api4('CaseType', 'get', [
          'select' => ['id'],
          'where' => [['title', '=', $name]],
          'checkPermissions' => FALSE,
          'limit' => 1,
        ]);
        $record = $result->first();
      }

      if ($record) {
        $id = $record['id'];
      }
    }

    $output->setParameter('id', $id);
  }

  /**
   * Returns the specification of the configuration options for this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag([]);
  }

  /**
   * Returns the specification of the parameters of this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getParameterSpecification() {
    return new SpecificationBag([
      new Specification('name', 'String', E::ts('Case type name'), TRUE),
    ]);
  }

  /**
   * Returns the specification of the output parameters of this action.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getOutputSpecification() {
    return new SpecificationBag([
      new Specification('id', 'Integer', E::ts('Case type id'), FALSE),
    ]);
  }

  /**
   * Returns the human readable title of this action.
   *
   * @return string
   */
  public function getTitle() {
    return E::ts('Get case type id by name');
  }

}
