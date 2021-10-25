<?php
declare(strict_types=1);

namespace App\Domain\Auction;

use App\Domain\User\User;
use DateTime;
use Exception;

use JsonSerializable;

class Auction implements JsonSerializable
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
     * @var string
     */
    private $type;

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
     * @param string $type
     * @param string $ruleset
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
        string $type,
        string $ruleset,
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
        $this->type = $type;
        $this->ruleset = $ruleset;
        $this->approver_id = $approver_id; // TODO rename xD
        $this->winner_id = $winner_id;
    }


    /**
     * Static factory method - instantiate Auctions from obj array returned by database layer
     *
     * @param array $AuctionRecords
     * @return array
     */
    public static function fromDbRecordArray(array $AuctionRecords) : array
    {
        $result = [];

        foreach ($AuctionRecords as $AuctionRecord)
        {
            $result[] = self::fromDbRecord($AuctionRecord);
        }

        return $result;
    }


    /**
     * Static factory method - instantiate Auction from obj returned by database layer
     *
     * @param object $AuctionRecord
     * @return Auction
     */
    public static function fromDbRecord(object $AuctionRecord) : Auction
    {
        try
        {
            return new Auction(
                $id = (int) $AuctionRecord->id,
                $name = $AuctionRecord->name,
                $date =  DateTime::createFromFormat("Y-m-d H:i:s", $AuctionRecord->date),
                $description = $AuctionRecord->description,
                $starting_bid = (int) $AuctionRecord->starting_bid,
                $time_limit = (isset($AuctionRecord->time_limit)) ?
                    DateTime::createFromFormat("H:i:s", $AuctionRecord->time_limit)
                    : null,
                $minimum_bid_increase = (int) $AuctionRecord->minimum_bid_increase,
                $bidding_interval = (isset($AuctionRecord->bidding_interval)) ?
                    DateTime::createFromFormat("H:i:s", $AuctionRecord->bidding_interval)
                    : null,
                $awaiting_approval = (bool) $AuctionRecord->awaiting_approval,
                $author_id = (int) $AuctionRecord->author_id,
                $ruleset = $AuctionRecord->ruleset,
                $type = $AuctionRecord->type,
                $approver_id = (int) $AuctionRecord->approver_id,
                $winner_id = (int) $AuctionRecord->winner_id
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
            "date" => $this->date,
            "description" => $this->description,
            "starting_bid" => $this->starting_bid,
            "time_limit" => $this->time_limit,
            "minimum_bid_increase" => $this->minimum_bid_increase,
            "bidding_interval" => $this->bidding_interval,
            "author_id" => $this->author_id,
            "author" => $this->author,
            "type" => $this->type,
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
    public function getAuthorId()
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
    public function getApproverId()
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
    public function getWinnerId()
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