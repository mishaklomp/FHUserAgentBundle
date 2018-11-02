<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\Response;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
final class Response
{
    /**
     * Header that communicates if the used version is permitted or not
     */
    public const HEADER_USER_AGENT_STATUS = 'X-User-Agent-Status';
}
