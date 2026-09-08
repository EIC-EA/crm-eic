<?php
declare(strict_types = 1);

namespace Civi\EicFpActionProvider\ActionProvider\Condition;

use Civi\ActionProvider\Condition\AbstractCondition;
use Civi\ActionProvider\Parameter\ParameterBagInterface;
use Civi\ActionProvider\Parameter\Specification;
use Civi\ActionProvider\Parameter\SpecificationBag;
use CRM_EicFpActionProvider_ExtensionUtil as E;

/**
 * Compares two parameters with a configurable operator.
 *
 * Unlike the built-in CompareParameterValue condition (which compares a
 * parameter against a fixed configuration value), this condition compares two
 * parameters against each other, so both sides can be mapped to form-processor
 * inputs or earlier action outputs.
 *
 * Supported operators: >, >=, <, <=, =, !=.
 *
 * When either value is numeric they are compared as numbers, with an
 * empty/missing value counting as 0; otherwise they are compared as (trimmed)
 * strings.
 */
class CompareParameters extends AbstractCondition {

  /**
   * Evaluate the condition.
   *
   * @param \Civi\ActionProvider\Parameter\ParameterBagInterface $parameterBag
   *   The parameters, with parameter1 and parameter2 mapped.
   *
   * @return bool
   */
  public function isConditionValid(ParameterBagInterface $parameterBag) {
    $parameter1 = $parameterBag->getParameter('parameter1');
    $parameter2 = $parameterBag->getParameter('parameter2');
    $comparison = html_entity_decode((string) $this->configuration->getParameter('comparison'), ENT_QUOTES, 'UTF-8');
    if ($comparison === '') {
      $comparison = '=';
    }

    if (is_numeric($parameter1) || is_numeric($parameter2)) {
      $value1 = is_numeric($parameter1) ? $parameter1 + 0 : 0;
      $value2 = is_numeric($parameter2) ? $parameter2 + 0 : 0;
    }
    else {
      $value1 = trim((string) ($parameter1 ?? ''));
      $value2 = trim((string) ($parameter2 ?? ''));
    }

    switch ($comparison) {
      case '>':
        return $value1 > $value2;

      case '>=':
        return $value1 >= $value2;

      case '<':
        return $value1 < $value2;

      case '<=':
        return $value1 <= $value2;

      case '!=':
        return $value1 != $value2;

      case '=':
      default:
        return $value1 == $value2;
    }
  }

  /**
   * Returns the specification of the configuration options for this condition.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag([
      new Specification('comparison', 'String', E::ts('Comparison'), TRUE, '=', NULL, [
        '>' => E::ts('Greater than'),
        '>=' => E::ts('Greater than or equal'),
        '<' => E::ts('Less than'),
        '<=' => E::ts('Less than or equal'),
        '=' => E::ts('Equal'),
        '!=' => E::ts('Not equal'),
      ], FALSE),
    ]);
  }

  /**
   * Returns the specification of the parameters of this condition.
   *
   * @return \Civi\ActionProvider\Parameter\SpecificationBag
   */
  public function getParameterSpecification() {
    return new SpecificationBag([
      new Specification('parameter1', 'String', E::ts('Parameter 1'), TRUE),
      new Specification('parameter2', 'String', E::ts('Parameter 2'), TRUE),
    ]);
  }

  /**
   * Returns the human readable title of this condition.
   *
   * @return string
   */
  public function getTitle() {
    return E::ts('Compare two parameters');
  }

}
