<?php
declare(strict_types=1);

namespace App\Domain\Bid;


interface BidRepository
{
    /**
     * Insert or update a Bid
     *
     * @param Bid $bid
     */
    public function save(Bid $bid): void;

    /**
     * @param int|null $bid_id
     * @return bool
     */
    public function bidExists(?int $bid_id): bool;

    /**
     * Delete Bid of id
     *
     * @param int $bid_id
     */
    public function delete(int $bid_id): void;

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
     * @param int $user_id
     * @return array
     */
    public function findAllUserBids(int $user_id): array;

    /**
     * @param int $auction_id
     * @return array
     */
    public function findAllAuctionBids(int $auction_id): array;

    /**
	 * @inheritDoc
	 */
	public function findAllRegistredUsers(int $auction_id): array;

    /**
	 * @inheritDoc
	 */
	public function findAllWaitingUsers(int $auction_id): array;
}
