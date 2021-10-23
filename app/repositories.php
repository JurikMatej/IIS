<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        // TODO ANY OTHER WRITTEN REPOSITORY FROM ~/src/Domain/* BELONGS HERE TO REGISTER & WORK
        
        // e.g. AuctionRepository::class => \DI\autowire(*::class) 
        // \DI\autowire = Dependency Injection
    ]);
};
