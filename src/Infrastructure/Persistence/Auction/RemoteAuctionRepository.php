<?php


namespace App\Infrastructure\Persistence\Auction;


use App\Domain\Auction\Auction;
use App\Domain\Auction\AuctionNotFoundException;
use App\Domain\Auction\AuctionRepository;
use App\Domain\User\User;
use App\Infrastructure\DBConnection;
use PDO;


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
     */
    public function findAll(): array
    {
        $all_auctions_stmt = $this->db_conn->prepare(self::SQL_GET_ALL_AUCTIONS);
        $all_auctions_stmt->execute();
        $all_auctions_result = $all_auctions_stmt->fetchAll();

        $all_auctions = Auction::fromDbRecordArray($all_auctions_result);

        foreach ($all_auctions as $auction)
        {
            // TODO Slow solution - overloads network on large amount of auction records
            // Consider using more complex SLQ commands to let the DB
            // build complete auction data
            $this->findAuctionPhotos($auction); // add photos in relation
            $this->findAuctionAuthor($auction); // add auction author
            $this->findAuctionApprover($auction); // add auciton approver
            $this->findAuctionWinner($auction); // add auction winner
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
        $this->findAuctionPhotos($auction_of_id); // add photos in relation
        $this->findAuctionAuthor($auction_of_id); // add auction author
        $this->findAuctionApprover($auction_of_id); // add auciton approver
        $this->findAuctionWinner($auction_of_id); // add auction winner

        return $auction_of_id;
    }

    /**
     * @inheritDoc
     */
    public function findAuctionPhotos(Auction $auction): Auction
    {
        $auction_photos_stmt = $this->db_conn->prepare(self::SQL_GET_AUCTION_PHOTOS);
        $auction_photos_stmt->execute(['id' => $auction->getId()]);
        $photos_object = $auction_photos_stmt->fetchAll();

        $photos_array = [];
        if ($photos_object)
        {
            // Transform $photos_object into more readable array form
            foreach ($photos_object as $photo)
            {
                $photos_array[] = $photo->path;
            }
        }

        $auction->setPhotos($photos_array);
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

    /**
     *
     */
    const SQL_GET_ALL_AUCTIONS = "
        SELECT * FROM auction
            INNER JOIN auction_type
                ON auction.type_id = auction_type.id
            INNER JOIN auction_ruleset
                ON auction.ruleset_id = auction_ruleset.id
    ";

    /**
     *
     */
    const SQL_GET_AUCTION_OF_ID = "
        SELECT * FROM auction
            INNER JOIN auction_type
                ON auction.type_id = auction_type.id
            INNER JOIN auction_ruleset
                ON auction.ruleset_id = auction_ruleset.id
        WHERE auction.id = :id
    ";

    /**
     *
     */
    const SQL_GET_AUCTION_PHOTOS = "
        SELECT path FROM auction_photo
        WHERE auction_id = :id
    ";

    /**
     *
     */
    const SQL_GET_AUCTION_USER_OF_ID = "
        SELECT * FROM user
            INNER JOIN user_role
                ON user.role_id = user_role.id
        WHERE user.id = :id
    ";


}