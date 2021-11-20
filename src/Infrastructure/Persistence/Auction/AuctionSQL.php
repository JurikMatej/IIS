<?php
declare(strict_types=1);


namespace App\Infrastructure\Persistence\Auction;

/**
 * @brief SQL Query constants' holder class for Auction entities
 *
 * Encapsulates all Auction related queries
 */
class AuctionSQL
{
	/**
	 * @brief Insert a new auction
	 */
	const INSERT_AUCTION = "
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


	/**
	 * @brief Insert new auction photos
	 */
	const INSERT_AUCTION_PHOTO = "
		INSERT INTO auction_photo
			(path, auction_id) 
		VALUES
			(:path, :auction_id);
	";


	/**
	 * @brief Update an existing auction
	 */
	const UPDATE_AUCTION = "
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


	/**
	 * @brief Update auction photos
	 * @todo  update corresponding auction as well ?
	 */
	const UPDATE_AUCTION_PHOTO = "
		UPDATE auction_photo
		SET
			path = :path
		WHERE
			id = :id;
	";


	/**
	 * @brief Delete an auction
	 */
	const DELETE_AUCTION = "
        DELETE FROM auction
        WHERE auction.id = :id
    ";


	/**
	 * @brief Get all auctions
	 */
	const GET_ALL_AUCTIONS = "
        SELECT
            a.id as id,
            a.name as name,
            a.date as date,
            a.description as description,
            a.starting_bid as starting_bid,
            a.time_limit as time_limit,
            a.minimum_bid_increase as minimum_bid_increase,
            a.bidding_interval as bidding_interval,
            a.awaiting_approval as awaiting_approval,
            a.author_id as author_id,
				author.first_name as author_first_name,
				author.last_name as author_last_name,
				author.mail as author_mail,
				author.password as author_password,
				author.address as author_address,
				author.registered_since as author_registered_since,
				author.role_id as author_role_id,
				author_role.role as author_role,
				author_role.authority_level as author_authority_level,
            a.type_id   as type_id,
            a_type.type as auction_type,
            a.ruleset_id as ruleset_id,
            a_ruleset.ruleset as auction_ruleset,
            a.approver_id as approver_id,
				approver.first_name as approver_first_name,
				approver.last_name as approver_last_name,
				approver.mail as approver_mail,
				approver.password as approver_password,
				approver.address as approver_address,
				approver.registered_since as approver_registered_since,
				approver.role_id as approver_role_id,
				approver_role.role as approver_role,
				approver_role.authority_level as approver_authority_level,
            a.winner_id as winner_id,
				winner.first_name as winner_first_name,
				winner.last_name as winner_last_name,
				winner.mail as winner_mail,
				winner.password as winner_password,
				winner.address as winner_address,
				winner.registered_since as winner_registered_since,
				winner.role_id as winner_role_id,
				winner_role.role as winner_role,
				winner_role.authority_level as winner_authority_level,
        	a_photo.auction_photo_id as auction_ids,
            a_photo.auction_photo_path as auction_photos
        FROM auction a
            INNER JOIN auction_type a_type
                ON a.type_id = a_type.id
        
            INNER JOIN auction_ruleset a_ruleset
                ON a.ruleset_id = a_ruleset.id
        
            # Can also be null after delete
            LEFT JOIN user author
                ON a.author_id = author.id
                    LEFT JOIN user_role author_role
                        ON author.role_id = author_role.id
        
            # Approver could be null
            LEFT JOIN user approver
                ON a.approver_id = approver.id
                    LEFT JOIN user_role approver_role
                        ON approver.role_id = approver_role.id
        
            # Winner could be null
            LEFT JOIN user winner
                ON a.winner_id = winner.id
                    LEFT JOIN user_role winner_role
                        ON winner.role_id = winner_role.id
        
            # Can have zero photos in relation
            LEFT JOIN (
                SELECT
					GROUP_CONCAT(ap.id) as auction_photo_id,
					GROUP_CONCAT(ap.path) as auction_photo_path,
					ap.auction_id as auction_id
				FROM auction_photo ap
				GROUP BY auction_id
            ) as a_photo
                ON a.id = a_photo.auction_id
        
        ORDER BY a.id;
    ";


    /**
	 * @brief Get all approved auctions
	 */
	const GET_ALL_APPROVED_AUCTIONS = "
        SELECT
            a.id as id,
            a.name as name,
            a.date as date,
            a.description as description,
            a.starting_bid as starting_bid,
            a.time_limit as time_limit,
            a.minimum_bid_increase as minimum_bid_increase,
            a.bidding_interval as bidding_interval,
            a.awaiting_approval as awaiting_approval,
            a.author_id as author_id,
				author.first_name as author_first_name,
				author.last_name as author_last_name,
				author.mail as author_mail,
				author.password as author_password,
				author.address as author_address,
				author.registered_since as author_registered_since,
				author.role_id as author_role_id,
				author_role.role as author_role,
				author_role.authority_level as author_authority_level,
            a.type_id   as type_id,
            a_type.type as auction_type,
            a.ruleset_id as ruleset_id,
            a_ruleset.ruleset as auction_ruleset,
            a.approver_id as approver_id,
				approver.first_name as approver_first_name,
				approver.last_name as approver_last_name,
				approver.mail as approver_mail,
				approver.password as approver_password,
				approver.address as approver_address,
				approver.registered_since as approver_registered_since,
				approver.role_id as approver_role_id,
				approver_role.role as approver_role,
				approver_role.authority_level as approver_authority_level,
            a.winner_id as winner_id,
				winner.first_name as winner_first_name,
				winner.last_name as winner_last_name,
				winner.mail as winner_mail,
				winner.password as winner_password,
				winner.address as winner_address,
				winner.registered_since as winner_registered_since,
				winner.role_id as winner_role_id,
				winner_role.role as winner_role,
				winner_role.authority_level as winner_authority_level,
        	a_photo.auction_photo_id as auction_ids,
            a_photo.auction_photo_path as auction_photos
        FROM auction a
            INNER JOIN auction_type a_type
                ON a.type_id = a_type.id
        
            INNER JOIN auction_ruleset a_ruleset
                ON a.ruleset_id = a_ruleset.id
        
            # Can also be null after delete
            LEFT JOIN user author
                ON a.author_id = author.id
                    LEFT JOIN user_role author_role
                        ON author.role_id = author_role.id
        
            # Approver could be null
            LEFT JOIN user approver
                ON a.approver_id = approver.id
                    LEFT JOIN user_role approver_role
                        ON approver.role_id = approver_role.id
        
            # Winner could be null
            LEFT JOIN user winner
                ON a.winner_id = winner.id
                    LEFT JOIN user_role winner_role
                        ON winner.role_id = winner_role.id
        
            # Can have zero photos in relation
            LEFT JOIN (
                SELECT
					GROUP_CONCAT(ap.id) as auction_photo_id,
					GROUP_CONCAT(ap.path) as auction_photo_path,
					ap.auction_id as auction_id
				FROM auction_photo ap
				GROUP BY auction_id
            ) as a_photo
                ON a.id = a_photo.auction_id
        
        WHERE a.awaiting_approval = 0

        ORDER BY a.id;
    ";

    /**
	 * @brief Get all approved auctions
	 */
	const GET_ALL_WAITING_AUCTIONS = "
    SELECT
        a.id as id,
        a.name as name,
        a.date as date,
        a.description as description,
        a.starting_bid as starting_bid,
        a.time_limit as time_limit,
        a.minimum_bid_increase as minimum_bid_increase,
        a.bidding_interval as bidding_interval,
        a.awaiting_approval as awaiting_approval,
        a.author_id as author_id,
            author.first_name as author_first_name,
            author.last_name as author_last_name,
            author.mail as author_mail,
            author.password as author_password,
            author.address as author_address,
            author.registered_since as author_registered_since,
            author.role_id as author_role_id,
            author_role.role as author_role,
            author_role.authority_level as author_authority_level,
        a.type_id   as type_id,
        a_type.type as auction_type,
        a.ruleset_id as ruleset_id,
        a_ruleset.ruleset as auction_ruleset,
        a.approver_id as approver_id,
            approver.first_name as approver_first_name,
            approver.last_name as approver_last_name,
            approver.mail as approver_mail,
            approver.password as approver_password,
            approver.address as approver_address,
            approver.registered_since as approver_registered_since,
            approver.role_id as approver_role_id,
            approver_role.role as approver_role,
            approver_role.authority_level as approver_authority_level,
        a.winner_id as winner_id,
            winner.first_name as winner_first_name,
            winner.last_name as winner_last_name,
            winner.mail as winner_mail,
            winner.password as winner_password,
            winner.address as winner_address,
            winner.registered_since as winner_registered_since,
            winner.role_id as winner_role_id,
            winner_role.role as winner_role,
            winner_role.authority_level as winner_authority_level,
        a_photo.auction_photo_id as auction_ids,
        a_photo.auction_photo_path as auction_photos
    FROM auction a
        INNER JOIN auction_type a_type
            ON a.type_id = a_type.id
    
        INNER JOIN auction_ruleset a_ruleset
            ON a.ruleset_id = a_ruleset.id
    
        # Can also be null after delete
        LEFT JOIN user author
            ON a.author_id = author.id
                LEFT JOIN user_role author_role
                    ON author.role_id = author_role.id
    
        # Approver could be null
        LEFT JOIN user approver
            ON a.approver_id = approver.id
                LEFT JOIN user_role approver_role
                    ON approver.role_id = approver_role.id
    
        # Winner could be null
        LEFT JOIN user winner
            ON a.winner_id = winner.id
                LEFT JOIN user_role winner_role
                    ON winner.role_id = winner_role.id
    
        # Can have zero photos in relation
        LEFT JOIN (
            SELECT
                GROUP_CONCAT(ap.id) as auction_photo_id,
                GROUP_CONCAT(ap.path) as auction_photo_path,
                ap.auction_id as auction_id
            FROM auction_photo ap
            GROUP BY auction_id
        ) as a_photo
            ON a.id = a_photo.auction_id
    
    WHERE a.awaiting_approval = 1

    ORDER BY a.id;
";


	/**
	 * @brief Get auction by id
	 */
	const GET_AUCTION_OF_ID = "
        SELECT
            a.id as id,
            a.name as name,
            a.date as date,
            a.description as description,
            a.starting_bid as starting_bid,
            a.time_limit as time_limit,
            a.minimum_bid_increase as minimum_bid_increase,
            a.bidding_interval as bidding_interval,
            a.awaiting_approval as awaiting_approval,
            a.author_id as author_id,
            author.first_name as author_first_name,
            author.last_name as author_last_name,
            author.mail as author_mail,
            author.password as author_password,
            author.address as author_address,
            author.registered_since as author_registered_since,
            author.role_id as author_role_id,
            author_role.role as author_role,
            author_role.authority_level as author_authority_level,
            a.type_id   as type_id,
            a_type.type as auction_type,
            a.ruleset_id as ruleset_id,
            a_ruleset.ruleset as auction_ruleset,
            a.approver_id as approver_id,
            approver.first_name as approver_first_name,
            approver.last_name as approver_last_name,
            approver.mail as approver_mail,
            approver.password as approver_password,
            approver.address as approver_address,
            approver.registered_since as approver_registered_since,
            approver.role_id as approver_role_id,
            approver_role.role as approver_role,
            approver_role.authority_level as approver_authority_level,
            a.winner_id as winner_id,
            winner.first_name as winner_first_name,
            winner.last_name as winner_last_name,
            winner.mail as winner_mail,
            winner.password as winner_password,
            winner.address as winner_address,
            winner.registered_since as winner_registered_since,
            winner.role_id as winner_role_id,
            winner_role.role as winner_role,
            winner_role.authority_level as winner_authority_level,
            a_photo.auction_photo_id as auction_ids,
            a_photo.auction_photo_path as auction_photos
        FROM auction a
            INNER JOIN auction_type a_type
                ON a.type_id = a_type.id
        
            INNER JOIN auction_ruleset a_ruleset
                ON a.ruleset_id = a_ruleset.id
        
            # Can also be null after delete
            LEFT JOIN user author
                ON a.author_id = author.id
                    LEFT JOIN user_role author_role
                        ON author.role_id = author_role.id
        
            # Approver could be null
            LEFT JOIN user approver
                ON a.approver_id = approver.id
                    LEFT JOIN user_role approver_role
                        ON approver.role_id = approver_role.id
        
            # Winner could be null
            LEFT JOIN user winner
                ON a.winner_id = winner.id
                    LEFT JOIN user_role winner_role
                        ON winner.role_id = winner_role.id
        
            LEFT JOIN (
                SELECT
					GROUP_CONCAT(ap.id) as auction_photo_id,
					GROUP_CONCAT(ap.path) as auction_photo_path,
					ap.auction_id as auction_id
				FROM auction_photo ap
				GROUP BY auction_id
            ) as a_photo
                ON a.id = a_photo.auction_id
        
        WHERE a.id = :id
        
        ORDER BY a.id;
    ";


	/**
	 * @brief Get auction photo by id
	 */
	const GET_AUCTION_PHOTO_OF_ID = "
		SELECT * FROM auction_photo
		WHERE id = :id;
	";


	/**
	 * @brief Get photo with the exact path and auction id
	 */
	const AUCTION_PHOTO_EXISTS = "
		SELECT 1 FROM auction_photo
		WHERE path = :path AND auction_id = :auction_id;
	";


	/**
	 * @brief Get last inserted auction's ID (auto_increment id)
	 */
	const GET_LAST_AUCTION = "
		SELECT MAX(id) as id
		FROM auction;
	";

    /**
	 * @brief Get all values from auction ruleset table
	 */
    const GET_AUCTION_RULESET = "
        SELECT * 
        FROM auction_ruleset;
    ";

    /**
	 * @brief Get all values from auction type table
	 */
    const GET_AUCTION_TYPE = "
        SELECT * 
        FROM auction_type;
    ";

     /**
	 * @brief Get all auctions of particular user
	 */
    const GET_AUCTIONS_OF_USER_ID = "
        SELECT * 
        FROM auction
        WHERE auction.author_id = :author_id;
    ";
}
