<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\EventListener;

use FH\Bundle\UserAgentBundle\Entity\UserAgent;
use FH\Bundle\UserAgentBundle\Repository\UserAgentRepositoryInterface;
use FH\Bundle\UserAgentBundle\Request\Request;
use FH\Bundle\UserAgentBundle\Response\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use function is_a;
use function sprintf;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
final class ResponseListener
{
    public const CRITERIA_HOST = 'host';

    private $userAgentRepository;
    private $criteria;

    public function __construct(UserAgentRepositoryInterface $userAgentRepository, array $criteria = [])
    {
        $this->userAgentRepository = $userAgentRepository;
        $this->criteria = $criteria;
    }

    public function __invoke(FilterResponseEvent $event): void
    {
        if ($this->hasCriteria(self::CRITERIA_HOST)) {
            if ($event->getRequest()->getHost() !== $this->getCriteria(self::CRITERIA_HOST)) {
                return;
            }
        }

        $userAgent = $this->userAgentRepository->find(
            $event->getRequest()->headers->get(Request::HEADER_USER_AGENT)
        );

        if (is_a($userAgent, UserAgent::class)) {
            $event->getResponse()->headers->set(
                Response::HEADER_USER_AGENT_STATUS, $userAgent->getAction()->getValue()
            );
        }
    }

    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $this->__invoke($event);
    }

    private function hasCriteria(string $value): bool
    {
        return isset($this->criteria[$value]);
    }

    /**
     * @return mixed
     */
    private function getCriteria(string $value)
    {
        if (!$this->hasCriteria($value)) {
            throw new \RuntimeException(sprintf("The criteria '%s' is not set, please configure it", $value));
        }

        return $this->criteria[$value];
    }
}
