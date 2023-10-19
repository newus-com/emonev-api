<?php

declare(strict_types=1);

use Monolog\Logger;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use App\Application\Token\Token;
use Monolog\Handler\StreamHandler;
use App\Domain\User\UserRepository;
use Monolog\Processor\UidProcessor;
use App\Application\Validator\Valid;
use Psr\Container\ContainerInterface;
use App\Application\Database\Database;
use App\Application\Token\TokenInterface;
use App\Application\Validator\ValidInterface;
use App\Application\Database\DatabaseInterface;
use App\Application\Middleware\DinasMiddleware;
use App\Application\Middleware\TokenMiddleware;
use App\Application\Settings\SettingsInterface;
use App\Application\Middleware\PermissionMiddleware;
use App\Application\Database\DatabaseTenantInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        ValidInterface::class => function (ContainerInterface $c) {
            $validator = new Valid();
            return $validator;
        },
        DatabaseInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $databaseSettings = $settings->get('primary');
            $database = new Database($databaseSettings['host'], $databaseSettings['database'], $databaseSettings['username'], $databaseSettings['password']);

            return $database;
        },
        DatabaseTenantInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $databaseSettings = $settings->get('tenant');
            $database = new Database($databaseSettings['host'], "dinas_".$databaseSettings['database'], $databaseSettings['username'], $databaseSettings['password']);

            return $database;
        },
        TokenInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $tokenSettings = $settings->get('token');
            $token = new Token($tokenSettings['key'], $tokenSettings['payload']);

            return $token;
        },
        TokenMiddleware::class => function (ContainerInterface $c) {
            $tokenInterface = $c->get(TokenInterface::class);
            $response = new \Slim\Psr7\Response();
            return new TokenMiddleware($tokenInterface, $response);
        },
        PermissionMiddleware::class => function (ContainerInterface $c) {
            $userRepository = $c->get(UserRepository::class);
            $response = new \Slim\Psr7\Response();
            return new PermissionMiddleware($response, $userRepository);
        },
        DinasMiddleware::class => function (ContainerInterface $c) {
            $userRepository = $c->get(UserRepository::class);
            $settings = $c->get(SettingsInterface::class);
            $response = new \Slim\Psr7\Response();
            return new DinasMiddleware($response, $c, $userRepository, $settings);
        }
    ]);
};
