<?php


namespace MyApp\Responder;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class Responder
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Create a new response.
     *
     * @return ResponseInterface The response
     */
    public function createResponse(): ResponseInterface
    {
        return $this->responseFactory->createResponse();
    }

    public function redirect(
        ResponseInterface $response,
        string $destination,
        array $queryParams = []
    ): ResponseInterface {
        if ($queryParams) {
            $destination = sprintf('%s?%s', $destination, http_build_query($queryParams));
        }

        return $response->withStatus(302)->withHeader('Location', $destination);
    }

    public function json(
        ResponseInterface $response,
        $data = null
    ): ResponseInterface {
        $response->getBody()->write((string)json_encode($data, JSON_PRETTY_PRINT));
        return $this->ok($response->withHeader('Content-Type', 'application/json'));
    }

    public function json_string(
        ResponseInterface $response,
        string $jsonString
    ): ResponseInterface {
        $response->getBody()->write($jsonString);
        return $this->ok($response->withHeader('Content-Type', 'application/json'));
    }

    public function unauthorized(
        ResponseInterface $response
    ): ResponseInterface {
        return $response->withStatus(401);
    }

    public function ok(
        ResponseInterface $response
    ): ResponseInterface {
        return $response->withStatus(200);
    }

    public function internal_server_error(
        ResponseInterface $response
    ): ResponseInterface {
        return $response->withStatus(500);
    }
}