<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\User\UserRepository;
use Psr\Container\ContainerInterface;
use App\Application\Database\Database;
use Psr\Http\Server\MiddlewareInterface;
use App\Application\Settings\SettingsInterface;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Database\DatabaseTenantInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class DinasMiddleware implements MiddlewareInterface
{

    protected Response $response;

    protected ContainerInterface $container;

    protected UserRepository $userRepository;

    protected SettingsInterface $settings;

    public function __construct(Response $response, ContainerInterface $container, UserRepository $userRepository, SettingsInterface $settings)
    {
        $this->response = $response;
        $this->container = $container;
        $this->userRepository = $userRepository;
        $this->settings = $settings;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $user = $request->getAttribute('user', []);
        if ($user == []) {
            $this->response->getBody()->write(json_encode(['status' => 401, 'message' => 'Harap login terlebih dahulu'], JSON_PRETTY_PRINT));
            return $this->response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        } else {
            // $dinas = $request->getHeaderLine('X-Dinas');
            $body = $request->getParsedBody();

            if ($user->status == 1) {
                if (!isset($body['dinasId'])) {
                    $this->response->getBody()->write(json_encode(['status' => 404, 'message' => 'Dinas tidak ditemukan'], JSON_PRETTY_PRINT));
                    return $this->response
                        ->withHeader('Content-Type', 'application/json')
                        ->withStatus(404);
                }
                $status = false;
                foreach ($user->dinas as $k => $v) {
                    if ($v['id'] == $body['dinasId']) {
                        $status = true;
                        continue;
                    }
                }
            } else {
                $status = true;
                if (isset($body['dinasId'])) {
                    $request = $request->withAttribute('Dinas', $body['dinasId']);
                }
            }
        }

        if (!$status) {
            $this->response->getBody()->write(json_encode(['status' => 404, 'message' => 'Dinas tidak ditemukan'], JSON_PRETTY_PRINT));
            return $this->response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        // $oldSettings = $this->settings->get('tenant');
        // $oldSettings['database'] = $body['dinasId'];
        // $this->settings->set('tenant',$oldSettings);

        // $request = $request->withAttribute(DatabaseTenantInterface::class, function (ContainerInterface $c, $dinas) {
        //     $settings = $c->get(SettingsInterface::class);

        //     $databaseSettings = $settings->get('tenant');
        //     $database = new Database($databaseSettings['host'], 'dinas_' . $databaseSettings['database'], $databaseSettings['username'], $databaseSettings['password']);

        //     return $database;
        // });
        return $handler->handle($request);
    }
}
