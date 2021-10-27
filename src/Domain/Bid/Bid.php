<?php
declare(strict_types=1);

namespace App\Domain\Bid;

use App\Domain\Auction\Auction;
use App\Domain\DomainInterfaces\DBRecordConstructable;
use App\Domain\User\User;
use Exception;
use JsonSerializable;

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
     * Bid constructor.
     * @param $id
     * @param int $auction_id
     * @param int $user_id
     * @param int $value
     */
    public function __construct($id, int $auction_id, int $user_id, int $value)
    {
        $this->id = $id;
        $this->auction_id = $auction_id;
        $this->user_id = $user_id;
        $this->value = $value;
    }


    public static function fromDbRecordArray(array $bidRecords) : array
    {
        $result = [];

        foreach ($bidRecords as $bidRecord)
        {
            $result[] = self::fromDbRecord($bidRecord);
        }

        return $result;
    }


    public static function fromDbRecord(object $bidRecord) : Bid
    {
        try
        {
            return new Bid(
                $id = (int) $bidRecord->id,
                $auction_id = (int) $bidRecord->auction_id,
                $user_id = (int) $bidRecord->user_id,
                $value = (int) $bidRecord->value
            );
        }
        catch (Exception $e)
        {
            // TODO 5XX Internal server error
            exit("Could not parse bid records from database in: __FILE__, __FUNCTION__ !");
        }
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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
     */
    public function setAuctionId(int $auction_id): void
    {
        $this->auction_id = $auction_id;
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
     */
    public function setAuction(Auction $auction): void
    {
        $this->auction = $auction;
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
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
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
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
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
            "value" => $this->value
        ];
    }
}