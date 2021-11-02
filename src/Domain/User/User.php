<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainInterfaces\DBRecordConstructable;
use App\Domain\DomainUtils\DomainUtils;
use DateTime;
use Exception;

use JsonSerializable;


/**
 *
 */
class User implements JsonSerializable, DBRecordConstructable
{
	/**
	 * @var int|null
	 */
	private $id;

	/**
	 * @var string
	 */
	private $first_name;

	/**
	 * @var string
	 */
	private $last_name;

	/**
	 * @var string
	 */
	private $mail;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $address;

	/**
	 * @var DateTime|null
	 */
	private $registered_since;

	/**
	 * @var int
	 */
	private $role_id;

	/**
	 * @var string
	 */
	private $role;

	/**
	 * @var int
	 */
	private $authority_level;


	/**
	 * @brief User constructor - private to ensure creation of User objects
	 *                               through static factory methods
	 */
	private function __construct()
	{
	}


	/**
	 * @brief Static parameterless factory
	 * @return User
	 */
	public static function create(): User
	{
		return new self();
	}


	/**
	 * Static factory method - instantiate Users from obj array returned by database layer
	 *
	 * @param array $userRecords
	 * @return array
	 */
	public static function fromDbRecordArray(array $userRecords): array
	{
		$result = [];

		foreach ($userRecords as $userRecord) {
			$result[] = self::fromDbRecord($userRecord);
		}

		return $result;
	}


	/**
	 * Static factory method - instantiate User from obj returned by database layer
	 *
	 * @param object $userRecord
	 * @return User
	 */
	public static function fromDbRecord(object $userRecord): User
	{
		try {
			return self::create()
				->setId((int)$userRecord->id)
				->setFirstName($userRecord->first_name)
				->setLastName($userRecord->last_name)
				->setMail($userRecord->mail)
				->setPassword($userRecord->password)
				->setAddress($userRecord->address)
				->setRegisteredSince(DomainUtils::createDateTime($userRecord->registered_since))
				->setRoleId((int)$userRecord->role_id)
				->setRole($userRecord->user_role)
				->setAuthorityLevel((int)$userRecord->user_authority_level);
		} catch (Exception $e) {
			// TODO 5XX Internal server error
			exit("Could not parse user records from database in: __FILE__, __FUNCTION__ !");
		}
	}


	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			"id" => $this->id,
			"first_name" => $this->first_name,
			"last_name" => $this->last_name,
			"mail" => $this->mail,
			"password" => $this->password,
			"address" => $this->address,
			"registered_since" => $this->getFormattedRegisteredSince(),
			"role_id" => $this->role_id,
			"role" => $this->role,
			"authority_level" => $this->authority_level
		];
	}


	/* FLUID STYLE GETTERS & SETTERS SECTION */

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param int|null $id
	 * @return User
	 */
	public function setId(?int $id): User
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstName(): string
	{
		return $this->first_name;
	}

	/**
	 * @param string $first_name
	 * @return User
	 */
	public function setFirstName(string $first_name): User
	{
		$this->first_name = ucfirst($first_name);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName(): string
	{
		return $this->last_name;
	}

	/**
	 * @param string $last_name
	 * @return User
	 */
	public function setLastName(string $last_name): User
	{
		$this->last_name = ucfirst($last_name);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMail(): string
	{
		return $this->mail;
	}

	/**
	 * @param string $mail
	 * @return User
	 */
	public function setMail(string $mail): User
	{
		$this->mail = $mail;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return User
	 *
	 * @todo A controller must hash pwd and set it via this func before
	 *       inserting / updating to db (cannot hash in this func)
	 */
	public function setPassword(string $password): User
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAddress(): string
	{
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return User
	 */
	public function setAddress(string $address): User
	{
		$this->address = $address;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getRegisteredSince(): DateTime
	{
		return $this->registered_since;
	}

	/**
	 * @return ?string
	 */
	public function getFormattedRegisteredSince(): ?string
	{
		return ($this->registered_since !== null) ?
			$this->registered_since->format("Y-m-d H:i:s")
			: null;
	}

	/**
	 * @param DateTime $registered_since
	 * @return User
	 */
	public function setRegisteredSince(DateTime $registered_since): User
	{
		$this->registered_since = $registered_since;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRoleId(): int
	{
		return $this->role_id;
	}

	/**
	 * @param int $role_id
	 * @return User
	 */
	public function setRoleId(int $role_id): User
	{
		$this->role_id = $role_id;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getRole(): string
	{
		return $this->role;
	}

	/**
	 * @param string $role
	 * @return User
	 */
	public function setRole(string $role): User
	{
		$this->role = $role;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAuthorityLevel(): int
	{
		return $this->authority_level;
	}

	/**
	 * @param int $authority_level
	 * @return User
	 */
	public function setAuthorityLevel(int $authority_level): User
	{
		$this->authority_level = $authority_level;
		return $this;
	}
}
