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

        $auction_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_OF_ID);
        $auction_stmt->execute(['id' => $auction_id]);

        return $auction_stmt->rowCount() !== 0;
    }

    /**
     * @param Auction $auction
     */
    private function insert(Auction $auction): void
    {
        $insert_stmt = $this->db_conn
            ->prepare(self::SQL_INSERT_AUCTION)
            ->execute([
                'name'          =>  $auction->getName(),
                'date'          =>  $auction->getFormattedDate(),
                'description'   =>  $auction->getDescription(),
                'starting_bid'  =>  $auction->getStartingBid(),
                'time_limit'    =>  $auction->getFormattedTimeLimit(),
                'minimum_bid_increase'  =>  $auction->getMinimumBidIncrease(),
                'bidding_interval'      =>  $auction->getFormattedBiddingInterval(),
                'awaiting_approval'     =>  $auction->isAwaitingApproval(),
                'author_id'     =>  $auction->getAuthorId(),
                'type_id'       =>  $auction->getTypeId(),
                'ruleset_id'    =>  $auction->getRulesetId(),
                'approver_id'   =>  $auction->getApproverId(),
                'winner_id'     =>  $auction->getWinnerId()
            ]);
    }

    /**
     * @param Auction $auction
     */
    private function update(Auction $auction): void
    {
        $update_stmt = $this->db_conn
            ->prepare(self::SQL_UPDATE_AUCTION)
            ->execute([
                'id'            =>  $auction->getId(),
                'name'          =>  $auction->getName(),
                'date'          =>  $auction->getFormattedDate(),
                'description'   =>  $auction->getDescription(),
                'starting_bid'  =>  $auction->getStartingBid(),
                'time_limit'    =>  $auction->getFormattedTimeLimit(),
                'minimum_bid_increase'  =>  $auction->getMinimumBidIncrease(),
                'bidding_interval'      =>  $auction->getFormattedBiddingInterval(),
                'awaiting_approval'     =>  $auction->isAwaitingApproval(),
                'author_id'     =>  $auction->getAuthorId(),
                'type_id'       =>  $auction->getTypeId(),
                'ruleset_id'    =>  $auction->getRulesetId(),
                'approver_id'   =>  $auction->getApproverId(),
                'winner_id'     =>  $auction->getWinnerId()
            ]);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $auction_id): void
    {
        $delete_stmt = $this->db_conn
            ->prepare(self::SQL_DELETE_AUCTION)
            ->execute(['id' => $auction_id]);
    }


    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $all_auctions_stmt = $this->db_conn->prepare(self::SQL_GET_ALL_AUCTIONS);
        $all_auctions_stmt->execute();
        $all_auctions_result = $all_auctions_stmt->fetchAll();

        $all_auctions = Auction::fromDbRecordArray($all_auctions_result);

        // TODO currently does not expaand type_id and resultset_id

        foreach ($all_auctions as $auction)
        {
            // TODO Slow solution - overloads network on large amount of auction records
            // Consider using more complex SLQ commands to let the DB
            // build complete auction data
            $this->expandForeignReferences($auction);
        }

        return $all_auctions;
    }

    /**
     * @inheritDoc
     */
    public function findAuctionOfId(int $id): Auction
    {
        $auction_of_id_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_OF_ID);
        $auction_of_id_stmt->execute(['id' => $id]);
        $auction_of_id_result = $auction_of_id_stmt->fetch();

        $auction_of_id = Auction::fromDbRecord($auction_of_id_result);

        // TODO FIXME with complex sql queries
        $auction_of_id->setType($auction_of_id_result->type);
        $auction_of_id->setRuleset($auction_of_id_result->type);

        return $this->expandForeignReferences($auction_of_id);
    }


    /**
     * @inheritDoc
     */
    public function expandForeignReferences(Auction $auction): Auction
    {
        $this->findAuctionPhotos($auction);     // add photos in relation
        $this->findAuctionAuthor($auction);     // add auction author
        $this->findAuctionApprover($auction);   // add auction approver
        $this->findAuctionWinner($auction);     // add auction winner

        return $auction;
    }


    /**
     * @inheritDoc
     */
    public function findAuctionPhotos(Auction $auction): Auction
    {
        $auction_photos_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_PHOTOS);
        $auction_photos_stmt->execute(['id' => $auction->getId()]);
        $photo_objects = $auction_photos_stmt->fetchAll();

        $photo_array = [];
        if ($photo_objects)
        {
            // Transform $photos_object into more readable array form
            foreach ($photo_objects as $photo_object)
            {
                $photo_array[] = $photo_object->path;
            }
        }

        $auction->setPhotos($photo_array);
        return $auction;
    }


    /**
     * @inheritDoc
     */
    public function findAuctionAuthor(Auction $auction): Auction
    {
        $auction_author_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_USER_OF_ID);
        $auction_author_stmt->execute(["id" => $auction->getAuthorId()]);
        $auction_author = User::fromDbRecord( $auction_author_stmt->fetch() );

        $auction->setAuthor($auction_author);

        return $auction;
    }


    /**
     * @inheritDoc
     */
    public function findAuctionApprover(Auction $auction): Auction
    {
        $auction_approver_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_USER_OF_ID);
        $auction_approver_stmt->execute(['id' => $auction->getApproverId()]);
        $auction_approver_result = $auction_approver_stmt->fetch();

        $auction_approver = ($auction_approver_result) ?
            User::fromDbRecord( $auction_approver_result )
            : null;

        $auction->setApprover($auction_approver ?? null);

        return $auction;
    }


    /**
     * @inheritDoc
     */
    public function findAuctionWinner(Auction $auction): Auction
    {
        $auction_winner_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_USER_OF_ID);
        $auction_winner_stmt->execute(['id' => $auction->getWinnerId()]);
        $auction_winner_result = $auction_winner_stmt->fetch();

        $auction_winner = ($auction_winner_result) ?
            User::fromDbRecord( $auction_winner_result )
            : null;

        $auction->setWinner($auction_winner ?? null);

        return $auction;
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



    /* QUERY CONSTANTS' SECTION */

    private const SQL_INSERT_AUCTION = "
        INSERT INTO auction
            (
             name, 
             date, 
             description, 
             starting_bid, 
             time_limit, 
             minimum_bid_increase,
             bidding_interval, 
             awaiting_approval, 
             author_id, 
             type_id, 
             ruleset_id, 
             approver_id, 
             winner_id
            )
        VALUES 
            (
             :name, 
             :date, 
             :description, 
             :starting_bid,
             :time_limit, 
             :minimum_bid_increase,
             :bidding_interval, 
             :awaiting_approval, 
             :author_id,
             :type_id, 
             :ruleset_id, 
             :approver_id, 
             :winner_id
            );
    ";

    private const SQL_UPDATE_AUCTION = "
        UPDATE auction
        SET 
            name = :name,
            date = :date,
            description = :description,
            starting_bid = :starting_bid,
            time_limit = :time_limit,
            minimum_bid_increase = :minimum_bid_increase,
            bidding_interval = :bidding_interval,
            awaiting_approval = :awaiting_approval,
            author_id = :author_id,
            type_id = :type_id,
            ruleset_id = :ruleset_id,
            approver_id = :approver_id,
            winner_id = :winner_id
        WHERE auction.id = :id;
    ";


    private const SQL_DELETE_AUCTION = "
        DELETE FROM auction
        WHERE auction.id = :id
    ";

    private const SQL_GET_ALL_AUCTIONS = "
        SELECT * FROM auction
            INNER JOIN auction_type
                ON auction.type_id = auction_type.id
            INNER JOIN auction_ruleset
                ON auction.ruleset_id = auction_ruleset.id
    ";

    private const SQL_GET_AUCTION_OF_ID = "
        SELECT * FROM auction
            INNER JOIN auction_type
                ON auction.type_id = auction_type.id
            INNER JOIN auction_ruleset
                ON auction.ruleset_id = auction_ruleset.id
        WHERE auction.id = :id
    ";

    private const SQL_GET_AUCTION_PHOTOS = "
        SELECT path FROM auction_photo
        WHERE auction_id = :id
    ";

    /**
     *  Get users in any kind of relation with auction of :id
     */
    private const SQL_GET_AUCTION_USER_OF_ID = "
        SELECT * FROM user
            INNER JOIN user_role
                ON user.role_id = user_role.id
        WHERE user.id = :id
    ";
}