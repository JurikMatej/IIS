<?php
declare(strict_types=1);

namespace App\Domain\Auction;

use App\Domain\DomainInterfaces\DBRecordConstructable;
use App\Domain\User\User;
use DateTime;
use Exception;

use JsonSerializable;

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
     * TODO reconsider changing to double (CZK? EUR?)
     */
    private $starting_bid;

    /**
     * @var DateTime|null
     */
    private $time_limit;

    /**
     * @var int
     */
    private $minimum_bid_increase;

    /**
     * @var DateTime|null
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
     * @var array
     */
    private $photos;

    /**
     * Auction constructor.
     * @param int|null $id
     * @param string $name
     * @param DateTime $date
     * @param string|null $description
     * @param int $starting_bid
     * @param DateTime|null $time_limit
     * @param int $minimum_bid_increase
     * @param DateTime|null $bidding_interval
     * @param bool $awaiting_approval
     * @param int $author_id
     * @param int $type_id
     * @param int $ruleset_id
     * @param int|null $approver_id
     * @param int|null $winner_id
     */
    public function __construct(
        ?int $id,
        string $name,
        DateTime $date,
        ?string $description,
        int $starting_bid,
        ?DateTime $time_limit,
        int $minimum_bid_increase,
        ?DateTime $bidding_interval,
        bool $awaiting_approval,
        int $author_id,
        int $type_id,
        int $ruleset_id,
        ?int $approver_id,
        ?int $winner_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->description = $description;
        $this->starting_bid = $starting_bid;
        $this->time_limit = $time_limit;
        $this->minimum_bid_increase = $minimum_bid_increase;
        $this->bidding_interval = $bidding_interval;
        $this->awaiting_approval = $awaiting_approval;
        $this->author_id = $author_id;
        $this->type_id = $type_id;
        $this->ruleset_id = $ruleset_id;
        $this->approver_id = $approver_id;
        $this->winner_id = $winner_id;
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

        foreach ($auctionRecords as $auctionRecord)
        {
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
        try
        {
            return new Auction(
                $id = (int) $auctionRecord->id,
                $name = $auctionRecord->name,
                $date =  DateTime::createFromFormat("Y-m-d H:i:s", $auctionRecord->date),
                $description = $auctionRecord->description,
                $starting_bid = (int) $auctionRecord->starting_bid,
                $time_limit = (isset($auctionRecord->time_limit)) ?
                    DateTime::createFromFormat("H:i:s", $auctionRecord->time_limit)
                    : null,
                $minimum_bid_increase = (int) $auctionRecord->minimum_bid_increase,
                $bidding_interval = (isset($auctionRecord->bidding_interval)) ?
                    DateTime::createFromFormat("H:i:s", $auctionRecord->bidding_interval)
                    : null,
                $awaiting_approval = (bool) $auctionRecord->awaiting_approval,
                $author_id = (int) $auctionRecord->author_id,
                $ruleset_id = (int) $auctionRecord->ruleset_id,
                $type_id = (int) $auctionRecord->type_id,
                $approver_id = (int) $auctionRecord->approver_id,
                $winner_id = (int) $auctionRecord->winner_id
            );
        }
        catch (Exception $e)
        {
            // TODO 5XX Internal server error
            exit("Could not parse auction records from database in: __FILE__, __FUNCTION__ !");
        }
    }


    /**
     * @return mixed|void
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


    /* GETTERS & SETTERS SECTION */

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
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
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
     */
    public function setStartingBid(int $starting_bid): void
    {
        $this->starting_bid = $starting_bid;
    }

    /**
     * @return DateTime|null
     */
    public function getTimeLimit(): ?DateTime
    {
        return $this->time_limit;
    }

    /**
     * @return string|null
     */
    public function getFormattedTimeLimit(): ?string
    {
        return ($this->time_limit !== null) ?
            $this->time_limit->format('H:i:s')
            : null;
    }

    /**
     * @param DateTime|null $time_limit
     */
    public function setTimeLimit(?DateTime $time_limit): void
    {
        $this->time_limit = $time_limit;
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
     */
    public function setMinimumBidIncrease(int $minimum_bid_increase): void
    {
        $this->minimum_bid_increase = $minimum_bid_increase;
    }

    /**
     * @return DateTime|null
     */
    public function getBiddingInterval(): ?DateTime
    {
        return $this->bidding_interval;
    }

    /**
     * @return string|null
     */
    public function getFormattedBiddingInterval(): ?string
    {
        return ($this->bidding_interval !== null) ?
            $this->bidding_interval->format('H:i:s')
            : null;
    }

    /**
     * @param DateTime|null $bidding_interval
     */
    public function setBiddingInterval(?DateTime $bidding_interval): void
    {
        $this->bidding_interval = $bidding_interval;
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
     */
    public function setAwaitingApproval(bool $awaiting_approval): void
    {
        $this->awaiting_approval = $awaiting_approval;
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
     */
    public function setTypeId(int $type_id): void
    {
        $this->type_id = $type_id;
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
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
     */
    public function setRulesetId(int $ruleset_id): void
    {
        $this->ruleset_id = $ruleset_id;
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
     */
    public function setRuleset(string $ruleset): void
    {
        $this->ruleset = $ruleset;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return User
     */
    public function getApprover(): User
    {
        return $this->approver;
    }

    /**
     * @param User|null $approver
     */
    public function setApprover(?User $approver): void
    {
        $this->approver = $approver;
    }

    /**
     * @return User
     */
    public function getWinner(): User
    {
        return $this->winner;
    }

    /**
     * @param User|null $winner
     */
    public function setWinner(?User $winner): void
    {
        $this->winner = $winner;
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
     */
    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
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
     */
    public function setApproverId(?int $approver_id): void
    {
        $this->approver_id = $approver_id;
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
     */
    public function setWinnerId(?int $winner_id): void
    {
        $this->winner_id = $winner_id;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @param array $photos
     */
    public function setPhotos(array $photos): void
    {
        $this->photos = $photos;
    }
}