<?php
declare(strict_types=1);

namespace App\Domain\User;

use DateTime;
use Exception;

use JsonSerializable; // Left here for possible implementation later

// TODO This is the User table Model - all other models should lie under ~/src/Domain
//      Model has its Entity class, any exceptions and it's own Repository interface

class User implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

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
     * @var DateTime
     */
    private $registered_since;

    /**
     * @var string
     */
    private $role;

    /**
     * @var int
     */
    private $authority_level;


    /**
     * @param int|null  $id
     * @param string    $firstName
     * @param string    $lastName
     * @param string    $mail
     * @param string    $password
     * @param string    $address
     * @param DateTime  $registered_since
     * @param string    $role
     * @param int       $authority_level
     */
    public function __construct(
        ?int $id,
        string $firstName, 
        string $lastName,
        string $mail,
        string $password,
        string $address,
        DateTime $registered_since,
        string $role,
        int $authority_level)
    {
        $this->id = $id;
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName);
        $this->mail     = $mail;
        $this->setPassword($password);
        $this->address  = $address;
        $this->registered_since = $registered_since; // ?
        $this->role     = $role;
        $this->authority_level = $authority_level;        
    }


    /**
     * Static factory method - instantiate Users from obj array returned by database layer
     *
     * @param array $UserRecords
     * @return array
     */
    public static function fromDbRecordArray(array $UserRecords) : array
    {
        $result = [];

        foreach ($UserRecords as $UserRecord)
        {
            $result[] = self::fromDbRecord($UserRecord);
        }

        return $result;
    }


    /**
     * Static factory method - instantiate User from obj returned by database layer
     *
     * @param object $UserRecord
     * @return User
     */
    public static function fromDbRecord(object $UserRecord) : User
    {
        // TODO Typecheck instead of try catch ?? DBRecords should always be Objects
        try
        {
            return new User(
                $id         = (int) $UserRecord->id,
                $firstName  = $UserRecord->first_name,
                $lastName   = $UserRecord->last_name,
                $mail       = $UserRecord->mail,
                $password   = $UserRecord->password,
                $address    = $UserRecord->address,
                // TODO Export datetime format to global definitions
                $registered_since = DateTime::createFromFormat("Y-m-d H:i:s", $UserRecord->registered_since),
                $role       = $UserRecord->role,
                $authority_level = (int) $UserRecord->authority_level
            );
        }
        catch (Exception $e)
        {
            // TODO 5XX Internal server error
            exit("Could not parse user records from database in: __FILE__, __FUNCTION__ !");
        }
    }


    /**
     * @return mixed|void
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "mail" => $this->mail,
            "password" => $this->password,
            "address" => $this->address,
            "registeredSince" => $this->registered_since->format('Y-m-d H:i:s'),
            "role"=> $this->role,
            "authority_level" => $this->authority_level
        ];
    }


    /* GETTERS & SETTERS SECTION */


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
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
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
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
     *
     * // TODO Either hash password here or create a separate function
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return DateTime
     */
    public function getRegisteredSince(): DateTime
    {
        return $this->registered_since;
    }

    /**
     * @param DateTime $registered_since
     */
    public function setRegisteredSince(DateTime $registered_since): void
    {
        $this->registered_since = $registered_since;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * // TODO Constraints to setting roles ??
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
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
     *
     * // TODO Constraints to setting authority level ??
     */
    public function setAuthorityLevel(int $authority_level): void
    {
        $this->authority_level = $authority_level;
    }
}

