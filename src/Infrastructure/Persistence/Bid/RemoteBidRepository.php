<?php
declare(strict_types=1);


namespace App\Infrastructure\Persistence\Bid;


use App\Domain\Auction\Auction;
use App\Domain\Bid\Bid;
use App\Domain\Bid\BidNotFoundException;
use App\Domain\Bid\BidRepository;
use App\Domain\User\User;
use App\Infrastructure\DBConnection;

/**
 * Class RemoteBidRepository
 * @package App\Infrastructure\Persistence\Bid
 */
class RemoteBidRepository implements BidRepository
{
	/**
	 * @var DBConnection
	 */
	private $db_conn;


	/**
	 * RemoteBidRepository constructor.
	 *
	 * @brief Get database connection instance
	 */
	public function __construct()
	{
		$this->db_conn = DBConnection::getInstance();
	}


	/**
	 * @inheritDoc
	 */
	public function save(Bid $bid): void
	{
		$bid_exists = $this->bidExists($bid->getId());

		if (!$bid_exists)
			$this->insert($bid);
		else
			$this->update($bid);
	}


	/**
	 * @inheritDoc
	 */
	public function bidExists(?int $bid_id): bool
	{
		if ($bid_id === null) return false;

		$bid_stmt = $this->db_conn->prepare(BidSQL::BID_EXISTS);
		$bid_stmt->execute(['id' => $bid_id]);

		return (bool)$bid_stmt->fetchColumn();
	}


	/**
	 * @param Bid $bid
	 */
	private function insert(Bid $bid): void
	{
		$this->db_conn
			->prepare(BidSQL::INSERT_BID)
			->execute([
				'value' => $bid->getValue(),
				'auction_id' => $bid->getAuctionId(),
				'user_id' => $bid->getUserId(),
				'awaiting_approval' => (int)$bid->getAwaitingApproval()
			]);
	}

	/**
	 * @param Bid $bid
	 */
	private function update(Bid $bid): void
	{
		$this->db_conn
			->prepare(BidSQL::UPDATE_BID)
			->execute([
				'id' => $bid->getId(),
				'value' => $bid->getValue(),
				'auction_id' => $bid->getAuctionId(),
				'user_id' => $bid->getUserId(),
				'awaiting_approval' => (int)$bid->getAwaitingApproval()
			]);
	}


	/**
	 * @inheritDoc
	 */
	public function delete(int $bid_id): void
	{
		$this->db_conn
			->prepare(BidSQL::DELETE_BID)
			->execute(['id' => $bid_id]);
	}


	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		$all_bids_stmt = $this->db_conn->prepare(BidSQL::GET_ALL_BIDS);
		$all_bids_stmt->execute();

		return Bid::fromDbRecordArray($all_bids_stmt->fetchAll());
	}


	/**
	 * @inheritDoc
	 */
	public function findBidOfId(int $id): Bid
	{
		$bid_of_id_stmt = $this->db_conn->prepare(BidSQL::GET_BID_OF_ID);
		$bid_of_id_stmt->execute(['id' => $id]);

		return Bid::fromDbRecord($bid_of_id_stmt->fetch());
	}


	/**
	 * @inheritDoc
	 */
	public function findAllUserBids(int $user_id): array
	{
		$all_user_bids_stmt = $this->db_conn->prepare(BidSQL::GET_USER_ALL_BIDS);
		$all_user_bids_stmt->execute(['id' => $user_id]);
		$all_user_bids = $all_user_bids_stmt->fetchAll();

		return Bid::fromDbRecordArray($all_user_bids);
	}


	/**
	 * @inheritDoc
	 */
	public function findAllAuctionBids(int $auction_id): array
	{
		$all_auction_bids_stmt = $this->db_conn->prepare(BidSQL::GET_AUCTION_ALL_BIDS);
		$all_auction_bids_stmt->execute(['id' => $auction_id]);
		$all_auction_bids = $all_auction_bids_stmt->fetchAll();

		return Bid::fromDbRecordArray($all_auction_bids);
	}


	/**
	 * @inheritDoc
	 */
	public function registerUser(int $auction_id, int $user_id): void
	{
		$this->db_conn
			->prepare(BidSQL::INSERT_BID)
			->execute([
				'value' => 0,
				'auction_id' => $auction_id,
				'user_id' => $user_id,
				'awaiting_approval' => 1
			]);
	}


	/**
	 * @inheritDoc
	 */
	public function registrationExists(int $auction_id, int $user_id): bool
	{
		$bid_stmt = $this->db_conn->prepare(BidSQL::REGISTRATION_EXISTS);
		$bid_stmt->execute(['user_id' => $user_id, 'auction_id' => $auction_id]);

		return (bool)$bid_stmt->fetchColumn();
	}


	/**
	 * @inheritDoc
	 */
	public function findBidByAuctionAndUserId(int $auction_id, int $user_id): ?object
	{
		$bid_stmt = $this->db_conn->prepare(BidSQL::GET_BID_OF_AUCTION_AND_USER_ID);
		$bid_stmt->execute(['user_id' => $user_id, 'auction_id' => $auction_id]);

		$bid = $bid_stmt->fetchAll();
		
		return ($bid)? Bid::fromDbRecordArray($bid)[0] : null;
	}


	/**
	 * @inheritDoc
	 */
	public function findAllRegistredUsers(int $auction_id): array
	{
		$bids_stmt = $this->db_conn->prepare(BidSQL::GET_AUCTION_ALL_BIDDING_USERS);
		$bids_stmt->execute(['id' => $auction_id, 'awaiting_approval' => 0]);

		$all_users = $bids_stmt->fetchAll();

		return Bid::fromDbRecordArray($all_users);
	}


	/**
	 * @inheritDoc
	 */
	public function findAllWaitingUsers(int $auction_id): array
	{
		$bids_stmt = $this->db_conn->prepare(BidSQL::GET_AUCTION_ALL_BIDDING_USERS);
		$bids_stmt->execute(['id' => $auction_id, 'awaiting_approval' => 1]);

		$all_users = $bids_stmt->fetchAll();

		return Bid::fromDbRecordArray($all_users);
	}


	/**
	 * @inheritDoc
	 */
	public function findHighestAuctionBid(int $auction_id): ?object
	{
		$bids_stmt = $this->db_conn->prepare(BidSQL::GET_AUCTION_ALL_BIDDING_USERS);
		$bids_stmt->execute(['id' => $auction_id, 'awaiting_approval' => 0]);

		$all_bids = $bids_stmt->fetchAll();

		return ($all_bids)? Bid::fromDbRecordArray($all_bids)[0] : null;
	}


	/**
	 * @inheritDoc
	 */
	public function findLowestAuctionBid(int $auction_id): ?object
	{
		$bids_stmt = $this->db_conn->prepare(BidSQL::GET_AUCTION_ALL_BIDDING_USERS);
		$bids_stmt->execute(['id' => $auction_id, 'awaiting_approval' => 0]);

		$all_bids = $bids_stmt->fetchAll();

		return ($all_bids) ? end(Bid::fromDbRecordArray($all_bids)) : null;
	}
}