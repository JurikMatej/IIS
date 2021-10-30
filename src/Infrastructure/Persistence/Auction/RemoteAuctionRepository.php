<?php


namespace App\Infrastructure\Persistence\Auction;


use App\Domain\Auction\Auction;
use App\Domain\Auction\AuctionNotFoundException;
use App\Domain\Auction\AuctionRepository;
use App\Domain\User\User;
use App\Infrastructure\DBConnection;
use PDO;


/**
 * Class RemoteAuctionRepository
 * @package App\Infrastructure\Persistence\Auction
 */
class RemoteAuctionRepository implements AuctionRepository
{
	/**
	 * @var PDO
	 */
	private $db_conn;


	/**
	 * RemoteAuctionRepository constructor.
	 *
	 * @brief Get database connection instance
	 */
	public function __construct()
	{
		$this->db_conn = DBConnection::getInstance();
	}


	/**
	 * @inheritDoc
	 *
	 * TODO implement saving photos
	 */
	public function save(Auction $auction): void
	{
		$auction_exists = $this->auctionExists($auction->getId());

		if (!$auction_exists)
			$this->insert($auction);
		else
			$this->update($auction);
	}


	/**
	 * @inheritDoc
	 */
	public function auctionExists(?int $auction_id): bool
	{
		if ($auction_id === null) return false;

		$auction_stmt = $this->db_conn->prepare(AuctionSQL::GET_AUCTION_OF_ID);
		$auction_stmt->execute(['id' => $auction_id]);

		return $auction_stmt->rowCount() !== 0;
	}


	/**
	 * @param Auction $auction
	 */
	private function insert(Auction $auction): void
	{
		$this->db_conn
			->prepare(AuctionSQL::INSERT_AUCTION)
			->execute([
				'name' => $auction->getName(),
				'date' => $auction->getFormattedDate(),
				'description' => $auction->getDescription(),
				'starting_bid' => $auction->getStartingBid(),
				'time_limit' => $auction->getFormattedTimeLimit(),
				'minimum_bid_increase' => $auction->getMinimumBidIncrease(),
				'bidding_interval' => $auction->getFormattedBiddingInterval(),
				'awaiting_approval' => $auction->isAwaitingApproval(),
				'author_id' => $auction->getAuthorId(),
				'type_id' => $auction->getTypeId(),
				'ruleset_id' => $auction->getRulesetId(),
				'approver_id' => $auction->getApproverId(),
				'winner_id' => $auction->getWinnerId()
			]);
	}


	/**
	 * @param Auction $auction
	 */
	private function update(Auction $auction): void
	{
		$this->db_conn
			->prepare(AuctionSQL::UPDATE_AUCTION)
			->execute([
				'id' => $auction->getId(),
				'name' => $auction->getName(),
				'date' => $auction->getFormattedDate(),
				'description' => $auction->getDescription(),
				'starting_bid' => $auction->getStartingBid(),
				'time_limit' => $auction->getFormattedTimeLimit(),
				'minimum_bid_increase' => $auction->getMinimumBidIncrease(),
				'bidding_interval' => $auction->getFormattedBiddingInterval(),
				'awaiting_approval' => $auction->isAwaitingApproval(),
				'author_id' => $auction->getAuthorId(),
				'type_id' => $auction->getTypeId(),
				'ruleset_id' => $auction->getRulesetId(),
				'approver_id' => $auction->getApproverId(),
				'winner_id' => $auction->getWinnerId()
			]);
	}


	/**
	 * @inheritDoc
	 */
	public function delete(int $auction_id): void
	{
		$this->db_conn
			->prepare(AuctionSQL::DELETE_AUCTION)
			->execute(['id' => $auction_id]);
	}


	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		$all_auctions_stmt = $this->db_conn->prepare(AuctionSQL::GET_ALL_AUCTIONS);
		$all_auctions_stmt->execute();
		$all_auctions_result = $all_auctions_stmt->fetchAll();

		return Auction::fromDbRecordArray($all_auctions_result);
	}


	/**
	 * @inheritDoc
	 */
	public function findAuctionOfId(int $id): Auction
	{
		$auction_of_id_stmt = $this->db_conn->prepare(AuctionSQL::GET_AUCTION_OF_ID);
		$auction_of_id_stmt->execute(['id' => $id]);
		$auction_of_id_result = $auction_of_id_stmt->fetch();

		return Auction::fromDbRecord($auction_of_id_result);

	}


	/**
	 * RemoteAuctionRepository destructor.
	 *
	 * @brief Erase reference to PDO database connection
	 */
	public function __destruct()
	{
		$this->db_conn = null;
	}
}