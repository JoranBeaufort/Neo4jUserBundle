<?php

namespace JoranBeaufort\Neo4jUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neo4j_user');
        
        $rootNode
            ->children()
                ->scalarNode('protocol')
                    ->defaultValue('http')
                ->end()
                ->scalarNode('username')
                    ->defaultValue('')
                ->end()
                ->scalarNode('password')
                    ->defaultValue('')
                ->end()
                ->scalarNode('url')
                    ->defaultValue('localhost')
                ->end()
                ->integerNode('port')
                    ->defaultValue(7474)
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
