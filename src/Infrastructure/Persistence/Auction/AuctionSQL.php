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
	 *
	 * Replace <@PHOTOS> with the actual values of required columns
	 */
	const INSERT_AUCTION_PHOTOS = "
		INSERT INTO auction_photo
			(path, auction_id) 
		VALUES
			<@PHOTOS>
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
	 *
	 * Replace <@PHOTO_IDS_CONSTRAINT> with the actual id constraints (id=photo[x]->id)
	 */
	const UPDATE_AUCTION_PHOTOS = "
		UPDATE auction_photo
		SET
			path = :path,
			auction_id = :auction_id
		WHERE
			<@PHOTO_IDS_CONSTRAINTS>
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
        
            INNER JOIN user author
                ON a.author_id = author.id
                    INNER JOIN user_role author_role
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
        
            INNER JOIN user author
                ON a.author_id = author.id
                    INNER JOIN user_role author_role
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
}
