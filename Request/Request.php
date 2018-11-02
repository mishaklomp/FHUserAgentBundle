<?php
declare(strict_types=1);

namespace FH\Bundle\UserAgentBundle\Request;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
final class Request
{
    /**
     * Header that contains the client user agent, which connects to the API
     */
    public const HEADER_USER_AGENT = 'User-Agent';
}
