<?php
declare(strict_types=1);

namespace App\Domain\Auction;


interface AuctionRepository
{
    /**
     * Insert or update an Auction
     *
     * @param Auction $auction
     */
    public function save(Auction $auction): void;


    /**
     * @param int $auction_id
     * @return bool
     */
    public function auctionExists(?int $auction_id): bool;


    /**
     * Delete Auction of id
     *
     * @param int $auction_id
     */
    public function delete(int $auction_id): void;

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
