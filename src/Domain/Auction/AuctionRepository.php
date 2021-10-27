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
     * Expand Auction foreign keys to actual objects in relation
     *
     * @param Auction $auction
     * @return Auction
     */
    public function expandForeignReferences(Auction $auction): Auction;


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionPhotos(Auction $auction): Auction;


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionAuthor(Auction $auction): Auction;


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionApprover(Auction $auction): Auction;


    /**
     * @param Auction $auction
     * @return Auction
     */
    public function findAuctionWinner(Auction $auction): Auction;
}
