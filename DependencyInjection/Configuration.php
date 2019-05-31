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
    private const ROOT_NAME = 'fh_user_agent';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NAME);

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root(self::ROOT_NAME);
        }

        $rootNode
            ->children()
                ->scalarNode('repository')
                    ->defaultValue(UserAgentRepository::class)
                    ->cannotBeEmpty()
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
