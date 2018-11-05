<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\DependencyInjection;

use FH\Bundle\UserAgentBundle\EventListener\ResponseListener;
use FH\Bundle\UserAgentBundle\Repository\UserAgentRepositoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
final class FHUserAgentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('repository.yaml');

        $this->configureRepository($container, $config['repository']);
        $this->configureResponseListener($container, $config['response_listener']);
    }

    private function configureRepository(ContainerBuilder $containerBuilder, string $repository): void
    {
        $containerBuilder->addAliases([
            UserAgentRepositoryInterface::class => $repository
        ]);
    }

    private function configureResponseListener(ContainerBuilder $containerBuilder, array $config): void
    {
        if (isset($config['criteria'])) {
            $criteria = $config['criteria'];
        } else {
            $criteria = [];
        }

        $containerBuilder->addDefinitions([
            $config['class'] =>
                (new Definition())
                    ->setAutoconfigured(true)
                    ->setAutowired(true)
                    ->setPublic(false)
                    ->addTag('kernel.event_listener',
                        [
                            'event' => KernelEvents::RESPONSE
                        ]
                    )
                    ->setBindings([
                        '$criteria' => $criteria
                    ])
                ]);
    }
}
