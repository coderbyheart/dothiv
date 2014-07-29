<?php

namespace Dothiv\BusinessBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dothiv_business');
        $rootNode
            ->children()
                ->arrayNode('allowed_tlds')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('clock_expr')->defaultValue('now')->end()
            ->arrayNode('attachments')
                ->children()
                    ->arrayNode('private')
                        ->children()
                            ->scalarNode('location')->isRequired()->end()
                        ->end()
                    ->end()
                    ->arrayNode('public')
                        ->children()
                            ->scalarNode('location')->isRequired()->end()
                            ->scalarNode('prefix')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('clickcounter')
                ->children()
                    ->scalarNode('baseurl')->defaultValue('https://dothiv-registry.appspot.com')->end()
                    ->scalarNode('secret')->isRequired()->end()
                    ->arrayNode('locales')->defaultValue(array('en','de','fr','es'))
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
            ->end();
        return $treeBuilder;
    }
}
