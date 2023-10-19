<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'primary' => [
                    'host' => $_ENV['DBHOST'],
                    'database' => $_ENV['DBNAME'],
                    'username' => $_ENV['DBUSER'],
                    'password' => $_ENV['DBPASS']
                ],
                'tenant' => [
                    'host' => $_ENV['DBHOST'],
                    'database' => 'emonev',
                    'username' => $_ENV['DBUSER'],
                    'password' => $_ENV['DBPASS']
                ],
                'token' => [
                    'key' => 'example_key',
                    'payload' => [
                        'access' => [
                            'iat' => time(),
                            'nbf' => 1357000000,
                            "exp" => time() + 86400,
                            'id' => ''
                        ],
                        'refresh' => [
                            'iat' => time(),
                            'nbf' => 1357000000,
                            "exp" => time() + (7 * 24 * 60 * 60),
                            'id' => ''
                        ]
                    ]
                ]
            ]);
        }
    ]);
};
