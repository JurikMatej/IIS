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
     * @param int|null $auction_id
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
	 * @inheritDoc
	 */
	public function findAllApproved(): array;

    /**
	 * @inheritDoc
	 */
	public function findAllWaitingForApproval(): array;

    /**
	 * @inheritDoc
	 */
	public function getAuctionTypes(): array;

    /**
	 * @inheritDoc
	 */
	public function getAuctionRulesets(): array;

    /**
	 * @inheritDoc
	 */
	public function getAuctionsOfUserID(int $author_id): array;

     /**
	 * @inheritDoc
	 */
	public function getAuctionsOfApproverID(int $approver_id): array;
}
