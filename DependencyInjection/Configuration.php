<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\DependencyInjection;

use FH\Bundle\UserAgentBundle\EventListener\ResponseListener;
use FH\Bundle\UserAgentBundle\Repository\UserAgentRepository;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('fh_user_agent');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('repository')
                    ->defaultValue(UserAgentRepository::class)
                ->end()
                ->arrayNode('response_listener')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue(ResponseListener::class)
                        ->end()
                        ->arrayNode('criteria')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
