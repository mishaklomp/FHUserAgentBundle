<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\Entity\Embedded;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
class Action
{
    public const ACTION_PERMIT  = 'permit';
    public const ACTION_UPGRADE = 'upgrade';

    private $value;

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
