<?php
declare(strict_types=1);

use App\Domain\Bid\BidRepository;
use App\Infrastructure\Persistence\Bid\RemoteBidRepository;
use DI\ContainerBuilder;

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\RemoteUserRepository;

use App\Domain\Auction\AuctionRepository;
use App\Infrastructure\Persistence\Auction\RemoteAuctionRepository;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our repository interfaces to their in memory implementations
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(RemoteUserRepository::class),
        AuctionRepository::class => \DI\autowire(RemoteAuctionRepository::class),
        BidRepository::class => \DI\autowire(RemoteBidRepository::class)
    ]);
};
