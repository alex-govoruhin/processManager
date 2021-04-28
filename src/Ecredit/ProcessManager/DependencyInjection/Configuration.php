<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('ecredit_process_manager');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('ecredit_process_manager');
        }

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('service')
                    ->children()
                        ->scalarNode('name')->end()
                        ->scalarNode('instance_name')->end()
                    ->end()
                ->end()
                ->arrayNode('commands')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('command')->end()
                            ->scalarNode('threads')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
