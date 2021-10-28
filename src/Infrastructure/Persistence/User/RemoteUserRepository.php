<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
//use App\Domain\User\UserNotFoundException;
use App\Infrastructure\DBConnection;
use PDO;

// TODO Under this parent directory lie implementations of ~/src/Domain/*MODEL*/*ModelRepository* repositories


class RemoteUserRepository implements UserRepository
{
    /**
     * @var PDO
     */
    private $db_conn;


    /**
     * RemoteUserRepository constructor.
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
    public function save(User $user): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @inheritDoc
     */
    public function delete(int $user_id): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $all_users_stmt = $this->db_conn->prepare(self::SQL_GET_ALL_USERS);
        $all_users_stmt->execute();

        $all_users = $all_users_stmt->fetchAll();

        return User::fromDbRecordArray($all_users);
    }

    /**
     * @inheritDoc
     */
    public function findUserOfId(int $id): User
    {
        $user_of_id_stmt = $this->db_conn->prepare(self::SQL_GET_USER_OF_ID);
        $user_of_id_stmt->execute(['id' => $id]);

        $user_of_id = $user_of_id_stmt->fetch();

        return User::fromDbRecord($user_of_id);
    }


    /**
     * Destructor
     *
     * @brief Erase reference to PDO database connection
     */
    public function __destruct()
    {
        $this->db_conn = null;
    }


    /* QUERY CONSTANTS' SECTION */

    /**
     * Query for user with their assigned role
     */
    const SQL_GET_ALL_USERS = "
        SELECT * FROM user
            INNER JOIN user_role
                ON user.role_id = user_role.id;
    ";

    /**
     * Query for user with id of :id and their assigned role
     */
    const SQL_GET_USER_OF_ID = "
        SELECT * FROM user
            INNER JOIN user_role
                ON user.role_id = user_role.id
            WHERE user.id=:id;
    ";
}