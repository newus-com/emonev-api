<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\User\UserRepository;
use Psr\Http\Server\MiddlewareInterface;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class PermissionMiddleware implements MiddlewareInterface
{

    protected UserRepository $userRepository;

    protected Response $response;

    public function __construct(Response $response, UserRepository $userRepository)
    {
        $this->response = $response;
        $this->userRepository = $userRepository;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $permission = [];

        $payload = $request->getAttribute('payload');
        if ($payload == null) {
            $this->response->getBody()->write(json_encode(['status' => 401, 'message' => 'Harap Login'], JSON_PRETTY_PRINT));
            return $this->response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
        try {
            $data = $this->userRepository->findOneByIdFullJoin($payload['id']);
            $user = $data->toArray();

            // var_dump($user['globalRole']);
            // die;
            if ($user['dinas'] != NULL) {
                foreach (json_decode($user['dinas'], TRUE) as $k => $v) {
                    foreach ($v['role'] as $r => $l) {
                        foreach ($v['permission'] as $p => $t) {
                            $permission[] = $t['name'];
                        }
                    }
                }
            }

            if ($user['globalRole'] != NULL) {
                foreach (json_decode($user['globalRole'], TRUE) as $k => $v) {
                    foreach ($v['permission'] as $p => $t) {
                        $permission[] = $t['name'];
                    }
                }
            }

            if ($user['specialPermission'] != NULL) {
                foreach (json_decode($user['specialPermission'], TRUE) as $s => $p) {
                    $permission[] = $p['name'];
                }
            }
            // foreach ($data->dinas as $k => $v) {
            //     foreach ($v->role as $r => $l) {
            //         foreach($l->permission as $p => $t) {
            //             $permission[] = $t['name'];
            //         }   
            //     }
            // }
            // foreach ($data->specialPermission as $s => $p) {
            //     $permission[] = $p['name'];
            // }

            $request = $request->withAttribute('user', $data);
            $request = $request->withAttribute('permission', $permission);
        } catch (Exception $e) {
            $this->response->getBody()->write(json_encode(['status' => 401, 'message' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $this->response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
        return $handler->handle($request);
    }
}
