<?php
declare(strict_types=1);

use DI\ContainerBuilder;

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\RemoteUserRepository;

use App\Domain\Auction\AuctionRepository;
use App\Infrastructure\Persistence\Auction\RemoteAuctionRepository;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our repository interfaces to their in memory implementations
    $containerBuilder->addDefinitions([
        // TODO Any other Repository from
        // ~/src/Domain/*MODEL*/*MODELREPO* belongs here to register

        // REPOSITORY WON'T WORK, NEITHER THE WHOLE APPLICATION, IF IT IS USED IN CODE,
        // BUT NOT REGISTERED

        UserRepository::class => \DI\autowire(RemoteUserRepository::class),
        AuctionRepository::class => \DI\autowire(RemoteAuctionRepository::class),
    ]);
};
