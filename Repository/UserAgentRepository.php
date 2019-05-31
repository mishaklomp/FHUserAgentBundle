<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use FH\Bundle\UserAgentBundle\Entity\UserAgent;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
class UserAgentRepository implements UserAgentRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $version): ?UserAgent
    {
        return $this->entityManager
            ->getRepository(UserAgent::class)
            ->find($version);
    }
}
