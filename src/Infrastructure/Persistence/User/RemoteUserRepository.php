<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

//use App\Domain\User\UserNotFoundException;
use App\Infrastructure\DBConnection;
use PDO;

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
		$user_exists = $this->userExists($user->getId());

		if (!$user_exists)
			$this->insert($user);
		else
			$this->update($user);
	}


	/**
	 * @inheritDoc
	 */
	public function userExists(?int $user_id): bool
	{
		if ($user_id === null) return false;

		$user_stmt = $this->db_conn->prepare(UserSQL::GET_USER_OF_ID);
		$user_stmt->execute(['id' => $user_id]);

		return $user_stmt->rowCount() !== 0;
	}


	/**
	 * @param User $user
	 */
	private function insert(User $user): void
	{
		$this->db_conn
			->prepare(UserSQL::INSERT_USER)
			->execute([
				'first_name' => $user->getFirstName(),
				'last_name' => $user->getLastName(),
				'mail' => $user->getMail(),
				'password' => $user->getPassword(),
				'address' => $user->getAddress(),
				'registered_since' => $user->getFormattedRegisteredSince(),
				'role_id' => $user->getRoleId(),
			]);
	}


	/**
	 * @param User $user
	 */
	private function update(User $user): void
	{
		$this->db_conn
			->prepare(UserSQL::UPDATE_USER)
			->execute([
				'id' => $user->getId(),
				'first_name' => $user->getFirstName(),
				'last_name' => $user->getLastName(),
				'mail' => $user->getMail(),
				'password' => $user->getPassword(),
				'address' => $user->getAddress(),
				'registered_since' => $user->getFormattedRegisteredSince(),
				'role_id' => $user->getRole(),
			]);
	}


	/**
	 * @inheritDoc
	 */
	public function delete(int $user_id): void
	{
		$this->db_conn
			->prepare(UserSQL::DELETE_USER)
			->execute(['id' => $user_id]);
	}


	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		$all_users_stmt = $this->db_conn->prepare(UserSQL::GET_ALL_USERS);
		$all_users_stmt->execute();
		$all_users = $all_users_stmt->fetchAll();

		return User::fromDbRecordArray($all_users);
	}


	/**
	 * @inheritDoc
	 */
	public function findUserOfId(int $id): User
	{
		$user_of_id_stmt = $this->db_conn->prepare(UserSQL::GET_USER_OF_ID);
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
}
