<?php

namespace A2lix\I18nDoctrineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author David ALLIX
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('a2lix_i18n_doctrine');

        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function($v) { return preg_split('/\s*,\s*/', $v); })
                    ->end()
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('manager_registry')->defaultValue('doctrine')->end()
//                ->booleanNode('enable_filters')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
