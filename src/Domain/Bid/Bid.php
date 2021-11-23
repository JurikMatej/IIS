<?php
declare(strict_types=1);

namespace App\Domain\Bid;

use App\Domain\Auction\Auction;
use App\Domain\DomainInterfaces\DBRecordConstructable;
use App\Domain\DomainUtils\DomainUtils;
use App\Domain\User\User;
use Exception;
use JsonSerializable;

/**
 *
 */
class Bid implements JsonSerializable, DBRecordConstructable
{
	/**
	 * int|null
	 */
	private $id;

	/**
	 * @var int
	 */
	private $auction_id;

	/**
	 * @var Auction
	 */
	private $auction;

	/**
	 * @var int
	 */
	private $user_id;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var int
	 */
	private $value;

	/**
	 * @var bool
	 */
	private $awaiting_approval;




	/**
	 * @brief Bid constructor - private to ensure creation of Bid objects
	 *                          through static factory methods
	 */
	private function __construct()
	{
	}


	/**
	 * @brief Static parameterless factory
	 * @return Bid
	 */
	public static function create(): Bid
	{
		return new self();
	}


	/**
	 * Static factory method - instantiate Bids from obj array returned by database layer
	 *
	 * @param array $bidRecords
	 * @return array
	 */
	public static function fromDbRecordArray(array $bidRecords): array
	{
		$result = [];

		foreach ($bidRecords as $bidRecord) {
			$result[] = self::fromDbRecord($bidRecord);
		}

		return $result;
	}


	/**
	 * Static factory method - instantiate Bid from obj returned by database layer
	 *
	 * @param object $bidRecord
	 * @return Bid
	 */
	public static function fromDbRecord(object $bidRecord): Bid
	{
		/* Monstrosity Ultima */
		try {
			// Create user object of the author of Bid's auction
			$bid_auction_author = ($bidRecord->author_first_name === null) ? null
			: User::create()
				->setId((int)$bidRecord->author_id)
				->setFirstName($bidRecord->author_first_name)
				->setLastName($bidRecord->author_last_name)
				->setMail($bidRecord->author_mail)
				->setPassword($bidRecord->author_password)
				->setAddress($bidRecord->author_address)
				->setRegisteredSince(
					DomainUtils::createDateTime($bidRecord->author_registered_since)
				)
				->setRoleId((int)$bidRecord->author_role_id)
				->setRole($bidRecord->author_role)
				->setAuthorityLevel((int)$bidRecord->author_authority_level);


			// Create user object of the approver of Bid's auction
			$bid_auction_approver = ($bidRecord->approver_first_name === null) ? null
				: User::create()
					->setId((int)$bidRecord->approver_id)
					->setFirstName($bidRecord->approver_first_name)
					->setLastName($bidRecord->approver_last_name)
					->setMail($bidRecord->approver_mail)
					->setPassword($bidRecord->approver_password)
					->setAddress($bidRecord->approver_address)
					->setRegisteredSince(
						DomainUtils::createDateTime($bidRecord->approver_registered_since)
					)
					->setRoleId((int)$bidRecord->approver_role_id)
					->setRole($bidRecord->approver_role)
					->setAuthorityLevel((int)$bidRecord->approver_authority_level);


			// Create user object of the winner of Bid's auction
			$bid_auction_winner = ($bidRecord->winner_first_name === null) ? null
				: User::create()
					->setId((int)$bidRecord->winner_id)
					->setFirstName($bidRecord->winner_first_name)
					->setLastName($bidRecord->winner_last_name)
					->setMail($bidRecord->winner_mail)
					->setPassword($bidRecord->winner_password)
					->setAddress($bidRecord->winner_address)
					->setRegisteredSince(
						DomainUtils::createDateTime($bidRecord->winner_registered_since)
					)
					->setRoleId((int)$bidRecord->winner_role_id)
					->setRole($bidRecord->winner_role)
					->setAuthorityLevel((int)$bidRecord->winner_authority_level);


			// Create object of the auction related to Bid
			$bid_auction = Auction::create()
				->setId((int)$bidRecord->bid_auction_id)
				->setName($bidRecord->auction_name)
				->setDate(DomainUtils::createDateTime($bidRecord->auction_date))
				->setDescription($bidRecord->auction_description)
				->setStartingBid((int)$bidRecord->auction_starting_bid)
				->setTimeLimit(DomainUtils::createTime($bidRecord->auction_time_limit))
				->setMinimumBidIncrease((int)$bidRecord->auction_minimum_bid_increase)
				->setBiddingInterval(DomainUtils::createTime($bidRecord->auction_bidding_interval))
				->setAwaitingApproval((bool)$bidRecord->auction_awaiting_approval)
				->setAuthorId((int)$bidRecord->author_id)
				->setAuthor($bid_auction_author)
				->setTypeId((int)$bidRecord->auction_type_id)
				->setType($bidRecord->auction_type)
				->setRulesetId((int)$bidRecord->auction_ruleset_id)
				->setRuleset($bidRecord->auction_ruleset)
				->setApproverId((int)$bidRecord->approver_id)
				->setApprover($bid_auction_approver)
				->setWinnerId((int)$bidRecord->winner_id)
				->setWinner($bid_auction_winner)
				->setPhotos(DomainUtils::parseAuctionPhotosRecord($bidRecord->auction_photos));


			// Create object of the user related to Bid
			$bid_user = ($bidRecord->user_first_name === null) ? null
			: User::create()
				->setId((int)$bidRecord->bid_user_id)
				->setFirstName($bidRecord->user_first_name)
				->setLastName($bidRecord->user_last_name)
				->setMail($bidRecord->user_mail)
				->setPassword($bidRecord->user_password)
				->setAddress($bidRecord->user_address)
				->setRegisteredSince(DomainUtils::createDateTime($bidRecord->user_registered_since))
				->setRoleId((int)$bidRecord->user_role_id)
				->setRole($bidRecord->user_role)
				->setAuthorityLevel((int)$bidRecord->user_authority_level);


			// Finally, create a Bid object to return
			return self::create()
				->setId((int)$bidRecord->id)
				->setAuctionId((int)$bidRecord->bid_auction_id)
				->setAuction($bid_auction)
				->setUserId((int)$bidRecord->bid_user_id)
				->setUser($bid_user)
				->setValue((int)$bidRecord->value)
				->setAwaitingApproval((bool)$bidRecord->awaiting_approval);
		} catch (Exception $e) {
			// Rethrow -> Let Application level handler do the work
			throw $e;
		}
	}


	/**
	 * Serialize Bid entity
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			"id" => $this->id,
			"auction_id" => $this->auction_id,
			"auction" => $this->auction,
			"user_id" => $this->user_id,
			"user" => $this->user,
			"value" => $this->value,
			"awaiting_approval" => $this->awaiting_approval
		];
	}


	/* FLUID STYLE GETTERS & SETTERS SECTION */

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return Bid
	 */
	public function setId($id): Bid
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAuctionId(): int
	{
		return $this->auction_id;
	}

	/**
	 * @param int $auction_id
	 * @return Bid
	 */
	public function setAuctionId(int $auction_id): Bid
	{
		$this->auction_id = $auction_id;
		return $this;
	}

	/**
	 * @return Auction
	 */
	public function getAuction(): Auction
	{
		return $this->auction;
	}

	/**
	 * @param Auction $auction
	 * @return Bid
	 */
	public function setAuction(Auction $auction): Bid
	{
		$this->auction = $auction;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 * @return Bid
	 */
	public function setUserId(int $user_id): Bid
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return ?User
	 */
	public function getUser(): ?User
	{
		return $this->user;
	}

	/**
	 * @param ?User $user
	 * @return Bid
	 */
	public function setUser(?User $user): Bid
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getValue(): int
	{
		return $this->value;
	}

	/**
	 * @param int $value
	 * @return Bid
	 */
	public function setValue(int $value): Bid
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getAwaitingApproval(): bool
	{
		return $this->awaiting_approval;
	}

	/**
	 * @param bool $awaiting_approval
	 * @return Bid
	 */
	public function setAwaitingApproval(bool $awaiting_approval): Bid
	{
		$this->awaiting_approval = $awaiting_approval;
		return $this;
	}
}
