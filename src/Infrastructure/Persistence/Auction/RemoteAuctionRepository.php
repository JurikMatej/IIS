<?php


namespace App\Infrastructure\Persistence\Auction;


use App\Domain\Auction\Auction;
use App\Domain\Auction\AuctionNotFoundException;
use App\Domain\Auction\AuctionRepository;
use App\Domain\AuctionPhoto\AuctionPhoto;
use App\Infrastructure\DBConnection;
use Exception;
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
	 * @throws Exception (TODO generate 5XX internal server error message & view)
	 */
	public function save(Auction $auction): void
	{
		$auction_exists = $this->auctionExists($auction->getId());
		$wasInserted = false;

		try {
			$this->db_conn->beginTransaction();

			if (!$auction_exists)
			{
				$this->insert($auction);
				$wasInserted = true;
			}
			else
			{
				$this->update($auction);
			}

			// Save auction related photos
			$photos = $auction->getPhotos();
			if ($wasInserted)
				$this->updateAuctionPhotosAfterNewAuctionInsert($photos);
			$this->saveAuctionPhotos($photos);

			$this->db_conn->commit();
		} catch (Exception $e) {
			$this->db_conn->rollBack();
			// TODO see @throws
			throw $e;
		}
	}


	/**
	 * @inheritDoc
	 */
	public function auctionExists(?int $auction_id): bool
	{
		if ($auction_id === null) return false;

		$auction_stmt = $this->db_conn->prepare(AuctionSQL::GET_AUCTION_OF_ID);
		$auction_stmt->execute(['id' => $auction_id]);

		return (bool)$auction_stmt->fetchColumn();
	}


	/**
	 * @param Auction $auction
	 * @pre Transaction mode must have begun
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
				'awaiting_approval' => (int)$auction->isAwaitingApproval(),
				'author_id' => $auction->getAuthorId(),
				'type_id' => $auction->getTypeId(),
				'ruleset_id' => $auction->getRulesetId(),
				'approver_id' => $auction->getApproverId(),
				'winner_id' => $auction->getWinnerId()
			]);
	}


	/**
	 * @param Auction $auction
	 * @pre Database transaction has started
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
				'awaiting_approval' => (int)$auction->isAwaitingApproval(),
				'author_id' => $auction->getAuthorId(),
				'type_id' => $auction->getTypeId(),
				'ruleset_id' => $auction->getRulesetId(),
				'approver_id' => $auction->getApproverId(),
				'winner_id' => $auction->getWinnerId()
			]);
	}


	/**
	 * @param $auctionPhotos[]
	 * @pre Database transaction has started
	 */
	private function saveAuctionPhotos(array $auctionPhotos): void
	{
		[$toInsert, $toUpdate] = $this->categorizeAuctionPhotosBySaveAction($auctionPhotos);

		$insert_photo_stmt = $this->db_conn->prepare(AuctionSQL::INSERT_AUCTION_PHOTO);
		$update_photo_stmt = $this->db_conn->prepare(AuctionSQL::UPDATE_AUCTION_PHOTO);

		foreach ($toInsert as $newPhoto)
		{
			$insert_photo_stmt->execute([
				"path" => $newPhoto->getPath(),
				"auction_id" => $newPhoto->getAuctionId()
			]);
		}

		foreach ($toUpdate as $editedPhoto)
		{
			$update_photo_stmt->execute([
				"id" => $editedPhoto->getId(),
				"path" => $editedPhoto->getPath()
			]);
		}
	}


	/**
	 * @brief Find out which of the auction photos already exist and return an array of their IDs
	 * @param array $auctionPhotos[]
	 * @return array of AuctionPhoto arrays
	 */
	private function categorizeAuctionPhotosBySaveAction(array $auctionPhotos): array
	{
		$toInsert = [];
		$toUpdate = [];

		foreach ($auctionPhotos as $auctionPhoto)
		{
			// Photo created by user controller has null id
			if ($auctionPhoto->getId() === null)
			{
				// Check whether a photo like that is already assigned to its auction
				// and if not, insert it as new (otherwise ignore it)
				if (!$this->auctionPhotoExists($auctionPhoto))
				{
					$toInsert[] = $auctionPhoto;
				}
				// Else toIgnore[] = $auctionPhoto; ;)
			}

			// ID already defined means record was selected from the database
			else
			{
				$toUpdate[] = $auctionPhoto;
			}
		}

		return [$toInsert, $toUpdate];
	}


	/**
	 * @param $auctionPhoto
	 * @return bool
	 */
	private function auctionPhotoExists($auctionPhoto): bool
	{
		$auction_has_photo_stmt = $this->db_conn->prepare(AuctionSQL::AUCTION_PHOTO_EXISTS);
		$auction_has_photo_stmt->execute([
			"path" => $auctionPhoto->getPath(),
			"auction_id" => $auctionPhoto->getAuctionId()
		]);

		return (bool)$auction_has_photo_stmt->fetchColumn();
	}


	/**
	 * @return int
	 */
	private function getLastInsertedAuctionId(): int
	{
		$last_auction_id_stmt = $this->db_conn->prepare(AuctionSQL::GET_LAST_AUCTION);
		$last_auction_id_stmt->execute();

		return (int)$last_auction_id_stmt->fetchColumn();
	}


	/**
	 * @brief After a new auction is assigned its ID after insert, update its photos to reference
	 * 		  that ID
	 * @param AuctionPhoto[]
	 */
	private function updateAuctionPhotosAfterNewAuctionInsert(array $auctionPhotos): void
	{
		$newAuctionId = $this->getLastInsertedAuctionId();
		foreach ($auctionPhotos as $auctionPhoto) {
			$auctionPhoto->setAuctionId($newAuctionId);
		}
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
	public function findAllWaitingForApproval(): array
	{
		$auctions_waiting_stmt = $this->db_conn->prepare(AuctionSQL::GET_ALL_WAITING_AUCTIONS);
		$auctions_waiting_stmt->execute();
		$auctions_waiting_result = $auctions_waiting_stmt->fetchAll();

		return Auction::fromDbRecordArray($auctions_waiting_result);
	}

	/**
	 * @inheritDoc
	 */
	public function findAllApproved(): array
	{
		$auctions_approved_stmt = $this->db_conn->prepare(AuctionSQL::GET_ALL_APPROVED_AUCTIONS);
		$auctions_approved_stmt->execute();
		$auctions_approved_result = $auctions_approved_stmt->fetchAll();

		return Auction::fromDbRecordArray($auctions_approved_result);
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
	 * @inheritDoc
	 */
	public function getAuctionTypes(): array
	{
		$auction_types_stmt = $this->db_conn->prepare(AuctionSQL::GET_AUCTION_TYPE);
		$auction_types_stmt->execute();
		$auction_types_result = $auction_types_stmt->fetchAll();

		return $auction_types_result;
	}


	/**
	 * @inheritDoc
	 */
	public function getAuctionRulesets(): array
	{
		$auction_rulesets_stmt = $this->db_conn->prepare(AuctionSQL::GET_AUCTION_RULESET);
		$auction_rulesets_stmt->execute();
		$auction_rulesets_result = $auction_rulesets_stmt->fetchAll();

		return $auction_rulesets_result;
	}

	/**
	 * @inheritDoc
	 */
	public function getAuctionsOfUserID(int $author_id): array
	{
		$user_auctions_stmt = $this->db_conn->prepare(AuctionSQL::GET_AUCTIONS_OF_USER_ID);
		$user_auctions_stmt->execute(['author_id' => $author_id]);
		$user_auctions_result = $user_auctions_stmt->fetchAll();

		return Auction::fromDbRecordArray($user_auctions_result);
	}

	/**
	 * @inheritDoc
	 */
	public function getAuctionsOfApproverID(int $approver_id): array
	{
		$approver_auctions_stmt = $this->db_conn->prepare(AuctionSQL::GET_AUCTIONS_OF_APPROVER_ID);
		$approver_auctions_stmt->execute(['approver_id' => $approver_id]);
		$approver_auctions_result = $approver_auctions_stmt->fetchAll();

		return Auction::fromDbRecordArray($approver_auctions_result);
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