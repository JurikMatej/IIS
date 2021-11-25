<?php
declare(strict_types=1);

namespace App\Domain\Auction;

use App\Domain\AuctionPhoto\AuctionPhoto;
use App\Domain\DomainInterfaces\DBRecordConstructable;
use App\Domain\DomainUtils\DomainUtils;
use App\Domain\User\User;
use DateTime;
use DateInterval;
use Exception;

use JsonSerializable;

/**
 *
 */
class Auction implements JsonSerializable, DBRecordConstructable
{
	/**
	 * @var int|null
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var DateTime
	 */
	private $date;

	/**
	 * @var string|null
	 */
	private $description;

	/**
	 * @var int
	 *
	 */
	private $starting_bid;

	/**
	 * @var DateInterval|null
	 */
	private $time_limit;

	/**
	 * @var int
	 */
	private $minimum_bid_increase;

	/**
	 * @var DateInterval|null
	 */
	private $bidding_interval;

	/**
	 * @var bool
	 */
	private $awaiting_approval;

	/**
	 * @var User|null
	 */
	private $author;

	/**
	 * @var int
	 */
	private $author_id;

	/**
	 * @var int
	 */
	private $type_id;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var int
	 */
	private $ruleset_id;

	/**
	 * @var string
	 */
	private $ruleset;

	/**
	 * @var User|null
	 */
	private $approver;

	/**
	 * @var int|null
	 */
	private $approver_id;

	/**
	 * @var User|null
	 */
	private $winner;

	/**
	 * @var int|null
	 */
	private $winner_id;

	/**
	 * @var AuctionPhoto[]
	 */
	private $photos;


	/**
	 * @brief Auction constructor - private to ensure creation of Auction objects
	 *                              through static factory methods
	 */
	private function __construct()
	{
	}


	/**
	 * @brief Static parameterless factory
	 * @return Auction
	 */
	public static function create(): Auction
	{
		return new self();
	}


	/**
	 * Static factory method - instantiate Auctions from obj array returned by database layer
	 *
	 * @param array $auctionRecords
	 * @return array
	 */
	public static function fromDbRecordArray(array $auctionRecords): array
	{
		$result = [];

		foreach ($auctionRecords as $auctionRecord) {
			$result[] = self::fromDbRecord($auctionRecord);
		}

		return $result;
	}


	/**
	 * Static factory method - instantiate Auction from obj returned by database layer
	 *
	 * @param object $auctionRecord
	 * @return Auction
	 */
	public static function fromDbRecord(object $auctionRecord): Auction
	{
		try {
			/* Monstrosity exhibition */
			// id can be set (deleted user), but first name will not be
			$author = ($auctionRecord->author_first_name === null) ? null
				: User::create()
					->setId((int)$auctionRecord->author_id)
					->setFirstName($auctionRecord->author_first_name)
					->setLastName($auctionRecord->author_last_name)
					->setMail($auctionRecord->author_mail)
					->setPassword($auctionRecord->author_password)
					->setAddress($auctionRecord->author_address)
					->setRegisteredSince(
						DomainUtils::createDateTime($auctionRecord->author_registered_since)
					)
					->setRoleId((int)$auctionRecord->author_role_id)
					->setRole($auctionRecord->author_role)
					->setAuthorityLevel((int)$auctionRecord->author_authority_level);

			// id can be set (deleted user), but first name will not be
			$approver = ($auctionRecord->approver_first_name === null) ? null
				: User::create()
					->setId((int)$auctionRecord->approver_id)
					->setFirstName($auctionRecord->approver_first_name)
					->setLastName($auctionRecord->approver_last_name)
					->setMail($auctionRecord->approver_mail)
					->setPassword($auctionRecord->approver_password)
					->setAddress($auctionRecord->approver_address)
					->setRegisteredSince(
						DomainUtils::createDateTime($auctionRecord->approver_registered_since)
					)
					->setRoleId((int)$auctionRecord->approver_role_id)
					->setRole($auctionRecord->approver_role)
					->setAuthorityLevel((int)$auctionRecord->approver_authority_level);

			// id can be set (deleted user), but first name will not be
			$winner = ($auctionRecord->winner_first_name === null) ? null
				: User::create()
					->setId((int)$auctionRecord->winner_id)
					->setFirstName($auctionRecord->winner_first_name)
					->setLastName($auctionRecord->winner_last_name)
					->setMail($auctionRecord->winner_mail)
					->setPassword($auctionRecord->winner_password)
					->setAddress($auctionRecord->winner_address)
					->setRegisteredSince(
						DomainUtils::createDateTime($auctionRecord->winner_registered_since)
					)
					->setRoleId((int)$auctionRecord->winner_role_id)
					->setRole($auctionRecord->winner_role)
					->setAuthorityLevel((int)$auctionRecord->winner_authority_level);

			return self::create()
				->setId((int)$auctionRecord->id)
				->setName($auctionRecord->name)
				->setDate(DomainUtils::createDateTime($auctionRecord->date))
				->setDescription($auctionRecord->description)
				->setStartingBid((int)$auctionRecord->starting_bid)
				->setTimeLimit(DomainUtils::createTime($auctionRecord->time_limit))
				->setMinimumBidIncrease((int)$auctionRecord->minimum_bid_increase)
				->setBiddingInterval(DomainUtils::createTime($auctionRecord->bidding_interval))
				->setAwaitingApproval((bool)$auctionRecord->awaiting_approval)
				->setAuthorId((int)$auctionRecord->author_id)
				->setAuthor($author)
				->setTypeId((int)$auctionRecord->type_id)
				->setType($auctionRecord->auction_type)
				->setRulesetId((int)$auctionRecord->ruleset_id)
				->setRuleset($auctionRecord->auction_ruleset)
				->setApproverId((int)$auctionRecord->approver_id)
				->setApprover($approver)
				->setWinnerId((int)$auctionRecord->winner_id)
				->setWinner($winner)
				->setPhotos(AuctionPhoto::fromAuctionDbRecord($auctionRecord));
		} catch (Exception $e) {
			// Rethrow -> Let Application level handler do the work
			throw $e;
		}
	}


	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			"id" => $this->id,
			"name" => $this->name,
			"date" => $this->getFormattedDate(),
			"description" => $this->description,
			"starting_bid" => $this->starting_bid,
			"time_limit" => $this->getFormattedTimeLimit(),
			"minimum_bid_increase" => $this->minimum_bid_increase,
			"bidding_interval" => $this->getFormattedBiddingInterval(),
			"author_id" => $this->author_id,
			"author" => $this->author,
			"type_id" => $this->type_id,
			"type" => $this->type,
			"ruleset_id" => $this->ruleset_id,
			"ruleset" => $this->ruleset,
			"approver_id" => $this->approver_id,
			"approver" => $this->approver,
			"winner_id" => $this->winner_id,
			"winner" => $this->winner,
			"photos" => $this->photos
		];
	}


	/* FLUID STYLE GETTERS & SETTERS SECTION */

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param int|null $id
	 * @return Auction
	 */
	public function setId(?int $id): Auction
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Auction
	 */
	public function setName(string $name): Auction
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getDate(): DateTime
	{
		return $this->date;
	}

	/**
	 * @return string|null
	 */
	public function getFormattedDate(): ?string
	{
		return ($this->date !== null) ?
			$this->date->format('Y-m-d H:i:s')
			: null;
	}

	/**
	 * @param DateTime $date
	 * @return Auction
	 */
	public function setDate(DateTime $date): Auction
	{
		$this->date = $date;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return Auction
	 */
	public function setDescription(string $description): Auction
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getStartingBid(): int
	{
		return $this->starting_bid;
	}

	/**
	 * @param int $starting_bid
	 * @return Auction
	 */
	public function setStartingBid(int $starting_bid): Auction
	{
		$this->starting_bid = $starting_bid;
		return $this;
	}

	/**
	 * @return DateInterval|null
	 */
	public function getTimeLimit(): ?DateInterval
	{
		return $this->time_limit;
	}

	/**
	 * @return string|null
	 */
	public function getFormattedTimeLimit(): ?string
	{
		if ($this->time_limit === null) return null;

		$days = intval($this->time_limit->format('%d'));
		$hours = intval($this->time_limit->format('%H')) + 24* $days;
		
		return $hours . ':' . $this->time_limit->format('%I:%S');
	}

	/**
	 * @param DateInterval|null $time_limit
	 * @return Auction
	 */
	public function setTimeLimit(?DateInterval $time_limit): Auction
	{
		$this->time_limit = $time_limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMinimumBidIncrease(): int
	{
		return $this->minimum_bid_increase;
	}

	/**
	 * @param int $minimum_bid_increase
	 * @return Auction
	 */
	public function setMinimumBidIncrease(int $minimum_bid_increase): Auction
	{
		$this->minimum_bid_increase = $minimum_bid_increase;
		return $this;
	}

	/**
	 * @return DateInterval|null
	 */
	public function getBiddingInterval(): ?DateInterval
	{
		return $this->bidding_interval;
	}

	/**
	 * @return string|null
	 */
	public function getFormattedBiddingInterval(): ?string
	{
		if ($this->bidding_interval === null) return null;

		return $this->bidding_interval->format('%H:%I');

	}

	/**
	 * @param DateInterval|null $bidding_interval
	 * @return Auction
	 */
	public function setBiddingInterval(?DateInterval $bidding_interval): Auction
	{
		$this->bidding_interval = $bidding_interval;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isAwaitingApproval(): bool
	{
		return $this->awaiting_approval;
	}

	/**
	 * @param bool $awaiting_approval
	 * @return Auction
	 */
	public function setAwaitingApproval(bool $awaiting_approval): Auction
	{
		$this->awaiting_approval = $awaiting_approval;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTypeId(): int
	{
		return $this->type_id;
	}

	/**
	 * @param int $type_id
	 * @return Auction
	 */
	public function setTypeId(int $type_id): Auction
	{
		$this->type_id = $type_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return Auction
	 */
	public function setType(string $type): Auction
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRulesetId(): int
	{
		return $this->ruleset_id;
	}

	/**
	 * @param int $ruleset_id
	 * @return Auction
	 */
	public function setRulesetId(int $ruleset_id): Auction
	{
		$this->ruleset_id = $ruleset_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRuleset(): string
	{
		return $this->ruleset;
	}

	/**
	 * @param string $ruleset
	 * @return Auction
	 */
	public function setRuleset(string $ruleset): Auction
	{
		$this->ruleset = $ruleset;
		return $this;
	}

	/**
	 * @return ?User
	 */
	public function getAuthor(): ?User
	{
		return $this->author;
	}

	/**
	 * @param ?User $author
	 * @return Auction
	 */
	public function setAuthor(?User $author): Auction
	{
		$this->author = $author;
		return $this;
	}

	/**
	 * @return User|null
	 */
	public function getApprover(): ?User
	{
		return $this->approver;
	}

	/**
	 * @param User|null $approver
	 * @return Auction
	 */
	public function setApprover(?User $approver): Auction
	{
		$this->approver = $approver;
		return $this;
	}

	/**
	 * @return User|null
	 */
	public function getWinner(): ?User
	{
		return $this->winner;
	}

	/**
	 * @param User|null $winner
	 * @return Auction
	 */
	public function setWinner(?User $winner): Auction
	{
		$this->winner = $winner;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAuthorId(): int
	{
		return $this->author_id;
	}

	/**
	 * @param int $author_id
	 * @return Auction
	 */
	public function setAuthorId(int $author_id): Auction
	{
		$this->author_id = $author_id;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getApproverId(): ?int
	{
		return $this->approver_id;
	}

	/**
	 * @param int|null $approver_id
	 * @return Auction
	 */
	public function setApproverId(?int $approver_id): Auction
	{
		$this->approver_id = $approver_id;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getWinnerId(): ?int
	{
		return $this->winner_id;
	}

	/**
	 * @param int|null $winner_id
	 * @return Auction
	 */
	public function setWinnerId(?int $winner_id): Auction
	{
		$this->winner_id = $winner_id;
		return $this;
	}

	/**
	 * @return AuctionPhoto[]
	 */
	public function getPhotos(): array
	{
		return $this->photos;
	}

	/**
	 * @param AuctionPhoto[] $photos
	 * @return Auction
	 */
	public function setPhotos(array $photos): Auction
	{
		$this->photos = $photos;
		return $this;
	}
}
