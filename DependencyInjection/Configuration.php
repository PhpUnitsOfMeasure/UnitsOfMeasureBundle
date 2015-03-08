<?php

namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('php_units_of_measure');

        $rootNode
            ->children()
                ->arrayNode('integrated')
                    ->info('Activate Quantity from PhpUnitOfMeasure here, like "Length" or "Time"')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('units')
                                ->info('Extend integrated quantity with more units.')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('to_native_factor')
                                            ->defaultValue(1)
                                        ->end()
                                        ->arrayNode('aliases')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('defined')
                    ->info('Define own quantities without writing code for them.')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('units')
                                ->info('Define unit for this quantity.')
                                ->prototype('array')
                                    ->children()
                                        ->enumNode('type')
                                            ->info('Only native and linear units can be defined here. Native means factor=1, while linear would be any factor!=1')
                                            ->defaultValue('linear')
                                            ->values(array('native', 'linear'))
                                        ->end()
                                        ->scalarNode('to_native_factor')
                                            ->info('This factor has to be numeric and will be used to convert this unit to the native one.')
                                            ->defaultValue(1)
                                        ->end()
                                        ->arrayNode('aliases')
                                            ->info('Define a list of possible aliases here, like "meter" could have [m, metre, metere]')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
