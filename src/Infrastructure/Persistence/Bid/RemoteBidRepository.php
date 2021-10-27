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
    public function findAll(): array
    {
        $all_bids_stmt = $this->db_conn->prepare(self::SQL_GET_ALL_BIDS);
        $all_bids_stmt->execute();
        $all_bids = Bid::fromDbRecordArray( $all_bids_stmt->fetchAll() );

        foreach ($all_bids as $bid)
        {
            $this->expandBidForeignReferences($bid);
        }

        return $all_bids;
    }

    /**
     * @inheritDoc
     */
    public function findBidOfId(int $id): Bid
    {
        $bid_of_id_stmt = $this->db_conn->prepare(self::SQL_GET_BID_OF_ID);
        $bid_of_id_stmt->execute(['id' => $id]);
        $bid_of_id = Bid::fromDbRecord( $bid_of_id_stmt->fetch() );

        return $this->expandBidForeignReferences($bid_of_id);
    }


    /**
     * @inheritDoc
     */
    public function expandBidForeignReferences(Bid $bid): Bid
    {
        $this->findBidUser($bid);       // add bids placer
        $this->findBidAuction($bid);    // add auction where the bid was placed

        return $bid;
    }


    /**
     * @inheritDoc
     */
    public function findBidUser(Bid $bid): Bid
    {
        $bid_user_stmt = $this->db_conn->prepare(self::SQL_GET_BID_USER);
        $bid_user_stmt->execute(['id' => $bid->getUserId()]);
        $bid_user = User::fromDbRecord( $bid_user_stmt->fetch() );

        $bid->setUser($bid_user);

        return $bid;
    }


    /**
     * @inheritDoc
     */
    public function findBidAuction(Bid $bid): Bid
    {
        $bid_auction_stmt = $this->db_conn->prepare(self::SQL_GET_BID_AUCTION);
        $bid_auction_stmt->execute(['id' => $bid->getAuctionId()]);
        $bid_auction = Auction::fromDbRecord( $bid_auction_stmt->fetch() );



        $bid->setAuction($bid_auction);

        return $bid;
    }


    /**
     * @inheritDoc
     */
    public function findAllUserBids(int $user_id) : array
    {
        $all_user_bids_stmt = $this->db_conn->prepare(self::SQL_GET_USER_BIDS);
        $all_user_bids_stmt->execute(['id' => $user_id]);
        $all_user_bids = $all_user_bids_stmt->fetchAll();

        return Bid::fromDbRecordArray($all_user_bids);
    }


    /**
     * @inheritDoc
     */
    public function findAllAuctionBids(int $auction_id): array
    {
        $all_auction_bids_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_BIDS);
        $all_auction_bids_stmt->execute(['id' => $auction_id]);
        $all_auction_bids = $all_auction_bids_stmt->fetchAll();

        return Bid::fromDbRecordArray($all_auction_bids);
    }


    /* QUERY CONSTANTS' SECTION */

    /**
     * Query for all bids
     */
    const SQL_GET_ALL_BIDS = "
        SELECT * FROM bid;
    ";

    /**
     * Query for bid of :id
     */
    const SQL_GET_BID_OF_ID = "
        SELECT * FROM bid
        WHERE bid.id = :id;
    ";

    /**
     * Query for bid placing user
     */
    const SQL_GET_BID_USER = "
        SELECT * FROM user
            INNER JOIN user_role
                ON user.role_id = user_role.id
        WHERE user.id = :id
    ";

    /**
     * Query for auction that bid was placed on
     */
    const SQL_GET_BID_AUCTION = "
        SELECT * FROM auction
            INNER JOIN auction_type
                ON auction.type_id = auction_type.id
            INNER JOIN auction_ruleset
                ON auction.ruleset_id = auction_ruleset.id
        WHERE auction.id = :id
    ";

    /**
     * Query for user of :id's bids
     */
    const SQL_GET_USER_BIDS = "
        SELECT * FROM bid 
        WHERE bid.user_id = :id
    ";

    /**
     * Query for auction of :id's bids
     */
    const SQL_GET_AUCTION_BIDS = "
        SELECT * FROM bid
        WHERE bid.auction_id = :id
    ";
}