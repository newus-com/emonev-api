<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use App\Application\Token\TokenInterface;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class TokenMiddleware implements MiddlewareInterface
{

    protected TokenInterface $token;

    protected Response $response;

    public function __construct(TokenInterface $token, Response $response)
    {
        $this->token = $token;
        $this->response = $response;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $authorization = $request->getHeaderLine('Authorization');

        if (empty($authorization)) {
            $this->response->getBody()->write(json_encode(['status' => 401, 'message' => 'Token tidak valid'], JSON_PRETTY_PRINT));
            return $this->response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }


        $token = str_replace('Bearer ', '', $authorization);

        try {
            $payload = $this->token->decode($token);
            $request = $request->withAttribute('payload', $payload);
            return $handler->handle($request);
        } catch (Exception $e) {
            $this->response->getBody()->write(json_encode(['status' => 401, 'message' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $this->response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }
}
