<?php


namespace MyApp\Controller;

use MyApp\Data\UserSessionData;
use MyApp\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use MyApp\Models\User;

final class AuthController
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @var Session
     */
    private $session;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param Session $session The session handler
     */
    public function __construct(Responder $responder, Session $session)
    {
        $this->responder = $responder;
        $this->session = $session;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array)$request->getParsedBody();
        $login_id = (string)($data['login_id'] ?? '');
        $password = (string)($data['password'] ?? '');

        $userSessionData = $this->authenticate($login_id, $password);

        if ($userSessionData) {
            $this->startUserSession($userSessionData);
            return $this->responder->json($response, $userSessionData);
        } else {
            return $this->responder->unauthorized($response);
        }
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->session->invalidate();
        return $this->responder->ok($response);
    }

    /**
     * Init user session.
     *
     * @param UserSessionData $userSessionData The user
     *
     * @return void
     */
    private function startUserSession(UserSessionData $userSessionData): void
    {
        // Clears all session data and regenerates session ID
        $this->session->invalidate();
        $this->session->start();
        $this->session->set('user', $userSessionData);
    }

    private function authenticate(string $login_id, string $password): ?UserSessionData {
        $userQuery = User::where('login_id', $login_id)
            ->where('password', $password);

        $out = fopen('php://stdout', 'w');
        fputs($out, $userQuery->toSql());
        $firstUser =  $userQuery->first();

        if (!$firstUser) {
            fputs($out, "No user matched");
            return null;
        }

        $user_id = $firstUser->id;
        fputs($out, "User matched");

        $userSessionData = new UserSessionData();
        $userSessionData->login_id = $firstUser->login_id;
        $userSessionData->nickname = $firstUser->nickname;
        $userSessionData->email = $firstUser->email;
        
        fclose($out);

        return $userSessionData;
    }
}
