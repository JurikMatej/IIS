<?php
declare(strict_types=1);


namespace App\Infrastructure\Persistence\User;

/**
 * @brief SQL Query constants' holder class for User Entities
 *
 * Encapsulates all User related queries
 */
class UserSQL
{
    /**
     * @brief Insert a new user
     */
    public const INSERT_USER = "
        INSERT INTO user
            (
             first_name,
             last_name,
             mail,
             password,
             address,
             registered_since,
             role_id)
        VALUES
            (
             :first_name,
             :last_name,
             :mail,
             :password,
             :address,
             :registered_since,
             :role_id);
    ";


    /**
     * @brief Update an existing user
     */
    public const UPDATE_USER = "
        UPDATE user
        SET 
            first_name = :first_name,
            last_name = :last_name,
            mail = :mail,
            password = :password,
            address = :address,
            registered_since = :registered_since,
            role_id = :role_id
        WHERE user.id = :id;
    ";


    /**
     * @brief Delete an user
     */
    public const DELETE_USER = "
        DELETE FROM user
        WHERE user.id = :id;
    ";

    /**
     * @brief Query for user with their assigned role
     */
    public const GET_ALL_USERS = "
        SELECT 
        	user.id as id,
        	user.first_name as first_name,
            user.last_name as last_name,
            user.mail as mail,
            user.password as password,
            user.address as address,
        	user.registered_since as registered_since,
			user.role_id as role_id,
            user_role.role as user_role,
            user_role.authority_level as user_authority_level
        FROM user
            INNER JOIN user_role
                ON user.role_id = user_role.id;
    ";

    /**
     * @brief for user with id of :id and their assigned role
     */
    public const GET_USER_OF_ID = "
        SELECT 
        	user.id as id,
        	user.first_name as first_name,
            user.last_name as last_name,
            user.mail as mail,
            user.password as password,
            user.address as address,
        	user.registered_since as registered_since,
			user.role_id as role_id,
            user_role.role as user_role,
            user_role.authority_level as user_authority_level
        FROM user
            INNER JOIN user_role
                ON user.role_id = user_role.id
            WHERE user.id=:id;
    ";


	public const USER_EXISTS = "
		SELECT 1 FROM user
		WHERE user.id = :id;
	";


    public const GET_USER_ROLES = "
        SELECT
            user_role.role,
            user_role.authority_level
        FROM user_role;
    ";
}
