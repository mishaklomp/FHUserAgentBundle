<?php
declare(strict_types=1);

namespace Tests\DependencyInjection;

use FH\Bundle\UserAgentBundle\DependencyInjection\FHUserAgentExtension;
use FH\Bundle\UserAgentBundle\EventListener\ResponseListener;
use FH\Bundle\UserAgentBundle\Repository\UserAgentRepository;
use FH\Bundle\UserAgentBundle\Repository\UserAgentRepositoryInterface;
use PHPUnit\Framework\Assert;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
final class FHUserAgentExtensionTest extends \PHPUnit\Framework\TestCase
{
    private $container;
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new FHUserAgentExtension();
    }

    public function tearDown()
    {
        unset($this->container, $this->extension);
    }

    public function testExtensionLoadedDefaults()
    {
        $this->extension->load([], $this->container);

        Assert::assertEquals(UserAgentRepository::class, $this->container->getAlias(UserAgentRepositoryInterface::class));
        Assert::assertContains(ResponseListener::class, $this->container->getServiceIds());
    }

    public function testConfigRepository(): void
    {
        $customRepositoryService = 'fh_app.repository.user_agent';

        $this->extension->load([
            'fh_user_agent' => [
                'repository' => $customRepositoryService
            ]
        ], $this->container);

        Assert::assertEquals($customRepositoryService, $this->container->getAlias(UserAgentRepositoryInterface::class));
    }

    public function testResponseListenerClass(): void
    {
        $customResponseListenerClass = 'fh_app.response_listener.class';

        $this->extension->load([
            'fh_user_agent' => [
                'response_listener' => [
                    'class' => $customResponseListenerClass
                ]
            ]
        ], $this->container);

        Assert::assertContains($customResponseListenerClass, $this->container->getServiceIds());
        Assert::assertNotContains(ResponseListener::class, $this->container->getServiceIds());
    }

    public function testResponseListenerCriteria(): void
    {
        $host = 'api.domain.tld';

        $this->extension->load([
            'fh_user_agent' => [
                'response_listener' => [
                    'criteria' => [
                        'host' => $host
                    ]
                ]
            ]
        ], $this->container);

        Assert::assertTrue(true);   // otherwise should throw exception, and internal property isn't testable
    }
}
