<?php
declare(strict_types=1);

namespace App\Domain\Bid;


interface BidRepository
{
    /**
     * @return Bid[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Bid
     * @throws BidNotFoundException
     */
    public function findBidOfId(int $id): Bid;

    /**
     * Expand Bid foreign keys to actual objects in relation
     *
     * @param Bid $bid
     * @return Bid
     */
    public function expandBidForeignReferences(Bid $bid): Bid;

    /**
     * @param Bid $bid
     * @return Bid
     */
    public function findBidUser(Bid $bid): Bid;

    /**
     * @param Bid $bid
     * @return Bid
     */
    public function findBidAuction(Bid $bid): Bid;

    /**
     * @param int $user_id
     * @return array
     */
    public function findAllUserBids(int $user_id): array;

    /**
     * @param int $auction_id
     * @return array
     */
    public function findAllAuctionBids(int $auction_id): array;


}