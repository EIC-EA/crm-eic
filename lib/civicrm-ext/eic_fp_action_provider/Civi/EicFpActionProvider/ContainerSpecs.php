<?php
declare(strict_types = 1);

namespace Civi\EicFpActionProvider;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Registers the eic_fp_action_provider conditions with the action provider.
 */
class ContainerSpecs implements CompilerPassInterface {

  /**
   * Register the eic_fp_action_provider conditions.
   */
  public function process(ContainerBuilder $container): void {
    if (!$container->hasDefinition('action_provider')) {
      return;
    }
    $actionProviderDefinition = $container->getDefinition('action_provider');

    // Register the CompareParameters condition (compares two parameters with an operator).
    $conditionDefinition = new Definition(\Civi\EicFpActionProvider\ActionProvider\Condition\CompareParameters::class);
    $actionProviderDefinition->addMethodCall(
      'addCondition',
      [$conditionDefinition]
    );

    // Register the GetCaseTypeIdByName action (resolves a case type id from its
    // machine name so Form Processors do not need to hardcode the numeric id).
    $actionProviderDefinition->addMethodCall(
      'addAction',
      [
        'GetCaseTypeIdByName',
        '\Civi\EicFpActionProvider\ActionProvider\Action\GetCaseTypeIdByName',
        'Get Case Type Id By Name'
      ]
    );

    // Register the GetActivityIdByCustomField action (resolves a single activity
    // id by matching a value against a named custom field, optionally restricted
    // to an activity type - all by name, so no numeric ids are hardcoded).
    $actionProviderDefinition->addMethodCall(
      'addAction',
      [
        'GetActivityIdByCustomField',
        '\Civi\EicFpActionProvider\ActionProvider\Action\GetActivityIdByCustomField',
        'Get Activity Id By Custom Field'
      ]
    );

    // Register the ActivityUpdateCustomData action (updates only the custom
    // field values of an existing activity by id, like CaseUpdateCustomData does
    // for cases - avoids CreateActivity clobbering source/target/status).
    $actionProviderDefinition->addMethodCall(
      'addAction',
      [
        'ActivityUpdateCustomData',
        '\Civi\EicFpActionProvider\ActionProvider\Action\ActivityUpdateCustomData',
        'Update Activity Custom Data'
      ]
    );
  }

}
