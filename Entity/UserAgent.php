<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\Entity;

use FH\Bundle\UserAgentBundle\Entity\Embedded\Action;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
class UserAgent
{
    private $version;
    private $action;

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getAction(): Action
    {
        return $this->action;
    }
}
