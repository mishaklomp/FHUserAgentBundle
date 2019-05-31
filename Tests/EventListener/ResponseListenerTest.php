<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\Tests\EventListener;

use FH\Bundle\UserAgentBundle\Entity\Embedded\Action;
use FH\Bundle\UserAgentBundle\Entity\UserAgent;
use FH\Bundle\UserAgentBundle\EventListener\ResponseListener;
use FH\Bundle\UserAgentBundle\Repository\UserAgentRepository;
use FH\Bundle\UserAgentBundle\Repository\UserAgentRepositoryInterface;
use FH\Bundle\UserAgentBundle\Request\Request as UserAgentRequest;
use FH\Bundle\UserAgentBundle\Response\Response as UserAgentResponse;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

use function is_string;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
final class ResponseListenerTest extends TestCase
{
    private const HOST = 'api.host.tld';

    private $dispatcher;
    private $kernel;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
        $this->kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
    }

    public function testFilterDoesNothingWhenUserAgentIsUnknown(): void
    {
        $event = $this->createEvent();
        $listener = $this->createListener();
        
        $this->addListenerAndDispatch($listener, $event);
        
        Assert::assertFalse($event->getResponse()->headers->has(UserAgentResponse::HEADER_USER_AGENT_STATUS));
    }

    public function testFilterUserAgentKnownActionPermit(): void
    {
        $versionPermit = 'v2.0.0';
        
        $event = $this->createEvent($this->createRequest($versionPermit));
        $listener = $this->createListener($versionPermit, Action::ACTION_PERMIT);
        
        $this->addListenerAndDispatch($listener, $event);
        
        Assert::assertTrue($event->getResponse()->headers->has(UserAgentResponse::HEADER_USER_AGENT_STATUS));
        Assert::assertEquals(Action::ACTION_PERMIT, $event->getResponse()->headers->get(UserAgentResponse::HEADER_USER_AGENT_STATUS));
    }

    public function testFilterUserAgentKnownActionUpgrade(): void
    {
        $versionUpgrade = 'v1.0.0';

        $event = $this->createEvent($this->createRequest($versionUpgrade));
        $listener = $this->createListener($versionUpgrade, Action::ACTION_UPGRADE);

        $this->addListenerAndDispatch($listener, $event);

        Assert::assertTrue($event->getResponse()->headers->has(UserAgentResponse::HEADER_USER_AGENT_STATUS));
        Assert::assertEquals(Action::ACTION_UPGRADE, $event->getResponse()->headers->get(UserAgentResponse::HEADER_USER_AGENT_STATUS));
    }

    /**
     * @return MockObject|UserAgentRepositoryInterface
     */
    private function mockUserAgentRepository(string $version = null, string $actionValue = null): MockObject
    {
        $userAgentRepository = $this->createMock(UserAgentRepository::class);

        if (is_string($version)) {
            $userAgent = $this->createMock(UserAgent::class);
            $action = $this->createMock(Action::class);

            $this->stubMethod($action, 'getValue', $actionValue);
            $this->stubMethod($userAgent, 'getAction', $action);
            $this->stubMethod($userAgent, 'getVersion', $version);
        } else {
            $userAgent = null;
        }

        $this->stubMethod($userAgentRepository, 'find', $userAgent);

        return $userAgentRepository;
    }

    private function createEvent(Request $request = null): FilterResponseEvent
    {
        $request = $request ?: new Request();

        return new FilterResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST, new Response('foo'));
    }

    private function createListener(string $version = null, string $actionValue = null): ResponseListener
    {
        $userAgentRepository = $this->mockUserAgentRepository($version, $actionValue);

        return new ResponseListener(
            $userAgentRepository,
[
                ResponseListener::CRITERIA_HOST => self::HOST
            ]
        );
    }

    private function createRequest(string $version): Request
    {
        $requestHeaders = [
            'HTTP_HOST' => self::HOST,
            'HTTP_' . UserAgentRequest::HEADER_USER_AGENT => $version
        ];

        return new Request([], [], [], [], [], $requestHeaders);
    }

    private function addListenerAndDispatch(ResponseListener $listener, FilterResponseEvent $event): FilterResponseEvent
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, [$listener, '__invoke']);
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        
        return $event;
    }

    private function stubMethod(\PHPUnit_Framework_MockObject_MockObject $object, string $method, $willReturn): void
    {
        $object
            ->method($method)
            ->willReturn($willReturn);
    }

    protected function tearDown(): void
    {
        $this->dispatcher = null;
        $this->kernel = null;
    }
}
