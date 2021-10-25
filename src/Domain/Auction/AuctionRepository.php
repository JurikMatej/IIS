<?php
declare(strict_types=1);

namespace App\Domain\Auction;


interface AuctionRepository
{
    /**
     * @return Auction[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Auction
     * @throws AuctionNotFoundException
     */
    public function findAuctionOfId(int $id): Auction;


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionPhotos(Auction $auction);


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionAuthor(Auction $auction);


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionApprover(Auction $auction);


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionWinner(Auction $auction);
}