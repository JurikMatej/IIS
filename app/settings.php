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
                'displayErrorDetails' => (bool) $_ENV['APP_DISPLAY_ERROR_DETAILS'],
                'logError'            => (bool) $_ENV['APP_DISPLAY_ERROR_DETAILS'],
                'logErrorDetails'     => (bool) $_ENV['APP_DISPLAY_ERROR_DETAILS'],
                'logger' => [
                    'name' => 'AuctionSystem',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ]);
        }
    ]);
};
