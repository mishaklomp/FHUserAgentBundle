<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\Repository;

use FH\Bundle\UserAgentBundle\Entity\UserAgent;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
interface UserAgentRepositoryInterface
{
    public function find(string $version): ?UserAgent;
}
