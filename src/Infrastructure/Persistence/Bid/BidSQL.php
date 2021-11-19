<?php
declare(strict_types=1);


namespace App\Infrastructure\Persistence\Bid;

/**
 * @brief SQL Query constants' holder class for Bid Entities
 *
 * Encapsulates all Bid related queries
 */
class BidSQL
{
	/**
	 * @brief Insert a new bid
	 */
	const INSERT_BID = "
        INSERT INTO bid
            (
             value, 
             auction_id, 
             user_id,
			 awaiting_approval
            )
        VALUES 
            (
             :value, 
             :auction_id, 
             :user_id,
			 :awaiting_approval
            );
    ";


	/**
	 * @brief Update an existing bid
	 */
	const UPDATE_BID = "
        UPDATE bid
        SET 
            value = :value,
            auction_id = :auction_id,
            user_id = :user_id,
			awaiting_approval = :awaiting_approval
        WHERE bid.id = :id;
    ";


	/**
	 * @brief Delete a bid
	 */
	const DELETE_BID = "
        DELETE FROM bid
        WHERE bid.id = :id
    ";


	/**
	 * @brief Query all bids
	 */
	const GET_ALL_BIDS = "
        SELECT 
        	bid.id as id,
            bid.value as value,
        	bid.auction_id as bid_auction_id,
			bid.awaiting_approval as awaiting_approval,
               
            # Auction in relation
            a.name as auction_name,
            a.date as auction_date,
            a.description as auction_description,
            a.starting_bid as auction_starting_bid,
            a.time_limit as auction_time_limit,
            a.minimum_bid_increase as auction_minimum_bid_increase,
            a.bidding_interval as auction_bidding_interval,
            a.awaiting_approval as auction_awaiting_approval,
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
			a.type_id   as auction_type_id,
            	a_type.type as auction_type,
            a.ruleset_id as auction_ruleset_id,
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
            a_photo.auction_photo_path as auction_photos,
              
            # User in relation   
        	bid.user_id as bid_user_id,
	            u.first_name as user_first_name,
				u.last_name as user_last_name,
				u.mail as user_mail,
				u.password as user_password,
				u.address as user_address,
				u.registered_since as user_registered_since,
				u.role_id as user_role_id,
				u_role.role as user_role,
				u_role.authority_level as user_authority_level
        
        FROM bid

        # Auction (whole expanded auction)
		INNER JOIN auction a 
		    ON bid.auction_id = a.id
				INNER JOIN auction_type a_type
                	ON a.type_id = a_type.id
        
				INNER JOIN auction_ruleset a_ruleset
					ON a.ruleset_id = a_ruleset.id

				INNER JOIN user author
					ON a.author_id = author.id
						INNER JOIN user_role author_role
                        	ON author.role_id = author_role.id
		
            	# Approver (could be null)
				LEFT JOIN user approver
					ON a.approver_id = approver.id
						LEFT JOIN user_role approver_role
							ON approver.role_id = approver_role.id

				# Winner (could be null)
				LEFT JOIN user winner
					ON a.winner_id = winner.id
						LEFT JOIN user_role winner_role
							ON winner.role_id = winner_role.id

				# Photos (could have zero photos in relation)
				LEFT JOIN (
					SELECT
						GROUP_CONCAT(DISTINCT ap.path) as auction_photo_path,
						ap.auction_id as auction_id
					FROM auction_photo ap
					GROUP BY auction_id
				) as a_photo
					ON a.id = a_photo.auction_id

		INNER JOIN user u
			ON bid.user_id = u.id
				INNER JOIN user_role u_role
					ON u.role_id = u_role.id
        
		ORDER BY bid.id;
    ";


	/**
	 * @brief Query bid by id
	 *
	 */
	const GET_BID_OF_ID = "
        SELECT 
        	bid.id as id,
            bid.value as value,
        	bid.auction_id as bid_auction_id,
			bid.awaiting_approval as awaiting_approval,
               
            # Auction in relation
            a.name as auction_name,
            a.date as auction_date,
            a.description as auction_description,
            a.starting_bid as auction_starting_bid,
            a.time_limit as auction_time_limit,
            a.minimum_bid_increase as auction_minimum_bid_increase,
            a.bidding_interval as auction_bidding_interval,
            a.awaiting_approval as auction_awaiting_approval,
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
			a.type_id   as auction_type_id,
            	a_type.type as auction_type,
            a.ruleset_id as auction_ruleset_id,
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
            a_photo.auction_photo_path as auction_photos,
              
            # User in relation   
        	bid.user_id as bid_user_id,
	            u.first_name as user_first_name,
				u.last_name as user_last_name,
				u.mail as user_mail,
				u.password as user_password,
				u.address as user_address,
				u.registered_since as user_registered_since,
				u.role_id as user_role_id,
				u_role.role as user_role,
				u_role.authority_level as user_authority_level
        
        FROM bid

        # Auction (whole expanded auction)
		INNER JOIN auction a 
		    ON bid.auction_id = a.id
				INNER JOIN auction_type a_type
                	ON a.type_id = a_type.id
        
				INNER JOIN auction_ruleset a_ruleset
					ON a.ruleset_id = a_ruleset.id

				INNER JOIN user author
					ON a.author_id = author.id
						INNER JOIN user_role author_role
                        	ON author.role_id = author_role.id
		
            	# Approver (could be null)
				LEFT JOIN user approver
					ON a.approver_id = approver.id
						LEFT JOIN user_role approver_role
							ON approver.role_id = approver_role.id

				# Winner (could be null)
				LEFT JOIN user winner
					ON a.winner_id = winner.id
						LEFT JOIN user_role winner_role
							ON winner.role_id = winner_role.id

				# Photos (could have zero photos in relation)
				LEFT JOIN (
					SELECT
						GROUP_CONCAT(DISTINCT ap.path) as auction_photo_path,
						ap.auction_id as auction_id
					FROM auction_photo ap
					GROUP BY auction_id
				) as a_photo
					ON a.id = a_photo.auction_id

		INNER JOIN user u
			ON bid.user_id = u.id
				INNER JOIN user_role u_role
					ON u.role_id = u_role.id

        WHERE bid.id = :id
        
		ORDER BY bid.id;
    ";


	const BID_EXISTS = "
		SELECT 1 from bid
		where bid.id = :id;
	";


	/**
	 * @brief Query for user of :id's bids
	 */
	const GET_USER_ALL_BIDS = "
        SELECT * FROM bid 
        WHERE bid.user_id = :id
    ";


	/**
	 * @brief Query for all auctions that have been bid on by a specific user
	 *
	 * @todo Implement
	 */
	public const GET_USER_ALL_BID_AUCTIONS = "
    
    ";


	/**
	 * @brief Query for auction of :id's bids
	 */
	const GET_AUCTION_ALL_BIDS = "
        SELECT 
			bid.id as id,
            bid.value as value,
        	bid.auction_id as bid_auction_id,
			bid.awaiting_approval as awaiting_approval,
		
		# Auction in relation
			a.name as auction_name,
			a.date as auction_date,
			a.description as auction_description,
			a.starting_bid as auction_starting_bid,
			a.time_limit as auction_time_limit,
			a.minimum_bid_increase as auction_minimum_bid_increase,
			a.bidding_interval as auction_bidding_interval,
			a.awaiting_approval as auction_awaiting_approval,
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
			a.type_id   as auction_type_id,
				a_type.type as auction_type,
			a.ruleset_id as auction_ruleset_id,
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
			a_photo.auction_photo_path as auction_photos,
				
			# User in relation   
			bid.user_id as bid_user_id,
				u.first_name as user_first_name,
				u.last_name as user_last_name,
				u.mail as user_mail,
				u.password as user_password,
				u.address as user_address,
				u.registered_since as user_registered_since,
				u.role_id as user_role_id,
				u_role.role as user_role,
				u_role.authority_level as user_authority_level
		
		FROM bid

		# Auction (whole expanded auction)
		INNER JOIN auction a 
			ON bid.auction_id = a.id
				INNER JOIN auction_type a_type
					ON a.type_id = a_type.id
		
				INNER JOIN auction_ruleset a_ruleset
					ON a.ruleset_id = a_ruleset.id

				LEFT JOIN user author
					ON a.author_id = author.id
						LEFT JOIN user_role author_role
							ON author.role_id = author_role.id
		
				# Approver (could be null)
				LEFT JOIN user approver
					ON a.approver_id = approver.id
						LEFT JOIN user_role approver_role
							ON approver.role_id = approver_role.id

				# Winner (could be null)
				LEFT JOIN user winner
					ON a.winner_id = winner.id
						LEFT JOIN user_role winner_role
							ON winner.role_id = winner_role.id

				# Photos (could have zero photos in relation)
				LEFT JOIN (
					SELECT
						GROUP_CONCAT(DISTINCT ap.path) as auction_photo_path,
						ap.auction_id as auction_id
					FROM auction_photo ap
					GROUP BY auction_id
				) as a_photo
					ON a.id = a_photo.auction_id

		LEFT JOIN user u
			ON bid.user_id = u.id
				LEFT JOIN user_role u_role
					ON u.role_id = u_role.id

		WHERE bid.auction_id = :id
        
		ORDER BY bid.value DESC;
    ";

	/**
	 * @brief Query for all users that have placed a bid on specific auction
	 *
	 * @todo Implement
	 */
	public const REGISTRATION_EXISTS = "
			SELECT 1 from bid
			WHERE bid.auction_id = :auction_id
			AND bid.user_id = :user_id;
    ";

	/**
	 * @brief Query for all users that have placed a bid on specific auction
	 *
	 * @todo Implement
	 */
	public const GET_AUCTION_ALL_BIDDING_USERS = "
		SELECT 
			bid.id as id,
			bid.value as value,
			bid.auction_id as bid_auction_id,
			bid.awaiting_approval as awaiting_approval,

		# Auction in relation
			a.name as auction_name,
			a.date as auction_date,
			a.description as auction_description,
			a.starting_bid as auction_starting_bid,
			a.time_limit as auction_time_limit,
			a.minimum_bid_increase as auction_minimum_bid_increase,
			a.bidding_interval as auction_bidding_interval,
			a.awaiting_approval as auction_awaiting_approval,
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
			a.type_id   as auction_type_id,
				a_type.type as auction_type,
			a.ruleset_id as auction_ruleset_id,
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
			a_photo.auction_photo_path as auction_photos,
				
			# User in relation   
			bid.user_id as bid_user_id,
				u.first_name as user_first_name,
				u.last_name as user_last_name,
				u.mail as user_mail,
				u.password as user_password,
				u.address as user_address,
				u.registered_since as user_registered_since,
				u.role_id as user_role_id,
				u_role.role as user_role,
				u_role.authority_level as user_authority_level

		FROM bid

		# Auction (whole expanded auction)
		INNER JOIN auction a 
			ON bid.auction_id = a.id
				INNER JOIN auction_type a_type
					ON a.type_id = a_type.id

				INNER JOIN auction_ruleset a_ruleset
					ON a.ruleset_id = a_ruleset.id

				LEFT JOIN user author
					ON a.author_id = author.id
						LEFT JOIN user_role author_role
							ON author.role_id = author_role.id

				# Approver (could be null)
				LEFT JOIN user approver
					ON a.approver_id = approver.id
						LEFT JOIN user_role approver_role
							ON approver.role_id = approver_role.id

				# Winner (could be null)
				LEFT JOIN user winner
					ON a.winner_id = winner.id
						LEFT JOIN user_role winner_role
							ON winner.role_id = winner_role.id

				# Photos (could have zero photos in relation)
				LEFT JOIN (
					SELECT
						GROUP_CONCAT(DISTINCT ap.path) as auction_photo_path,
						ap.auction_id as auction_id
					FROM auction_photo ap
					GROUP BY auction_id
				) as a_photo
					ON a.id = a_photo.auction_id

		LEFT JOIN user u
			ON bid.user_id = u.id
				LEFT JOIN user_role u_role
					ON u.role_id = u_role.id

		WHERE bid.auction_id = :id
		AND bid.awaiting_approval = :awaiting_approval

		ORDER BY bid.value DESC;
	";


	/**
	 * @brief Query for a bid with the highest value placed on a specific auction
	 *
	 * @todo Implement
	 */
	public const GET_AUCTION_HIGHEST_BID = "

    ";
}
