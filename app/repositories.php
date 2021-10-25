<?php
declare(strict_types=1);

use DI\ContainerBuilder;

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\RemoteUserRepository;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(RemoteUserRepository::class),

        // TODO Any other Repository from
        // ~/src/Domain/*MODEL*/*MODELREPO* belongs here to register

        // REPOSITORY WON'T WORK, NEITHER THE WHOLE APPLICATION, IF IT IS USED IN CODE,
        // BUT NOT REGISTERED
    ]);
};
