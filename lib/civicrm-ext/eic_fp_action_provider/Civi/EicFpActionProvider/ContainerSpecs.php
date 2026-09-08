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
  }

}
