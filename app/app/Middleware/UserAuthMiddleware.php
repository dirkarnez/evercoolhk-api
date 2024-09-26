<?php

namespace MyApp\Middleware;

use MyApp\Data\UserSessionData;
use MyApp\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Middleware.
 */
final class UserAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param SessionInterface $session The session
     */
    public function __construct(
        Responder $responder,
        SessionInterface $session
    ) {
        $this->responder = $responder;
        $this->session = $session;
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userSessionData = $this->session->get('user');

        if ($userSessionData instanceof UserSessionData) {
            return $handler->handle($request);
        } else {
            return $this->responder->unauthorized($this->responder->createResponse());
        }
    }
}