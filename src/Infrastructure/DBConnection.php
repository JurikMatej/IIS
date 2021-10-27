<?php


namespace App\Infrastructure;

use PDO;
use PDOException;


/**
 * Class DBConnection
 * @package App\Infrastructure
 *
 * Implements a singleton interface for PDO database connection
 * to be used in Database Repositories
 */
class DBConnection
{
    /**
     * @var DBConnection|null singleton
     */
    private static $db;

    /**
     * @var PDO|null
     */
    private $conn;

    /**
     * @var string
     */
    private $db_dsn;

    /**
     * @var string
     */
    private $db_user;

    /**
     * @var string
     */
    private $db_pass;


    /**
     * DBConnection constructor defined explicitly as private to prevent instantiating this class
     *
     * @brief Create database connection
     */
    private final function __construct()
    {
        $this->db_dsn  = $_ENV['DB_DSN'];
        $this->db_user = $_ENV['DB_USER'];
        $this->db_pass = $_ENV['DB_PASS'];

        try
        {
            $this->establishConnection();
        }
        catch (PDOException $e)
        {
            // TODO implement 5XX Response generation
            exit("Database Connection Failed");
        }
    }

    /**
     * @brief Create a new PDO connection object and assign it to member variable
     */
    private function establishConnection()
    {
        $this->conn = new PDO($this->db_dsn, $this->db_user, $this->db_pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }


    /**
     * DBConnection destructor
     *
     * @brief Disconnect database connection
     */
    public function __destruct()
    {
        self::$db = null;
        $this->conn = null;
    }


    /**
     * @return PDO
     */
    public static function getInstance() : PDO
    {
        if (!isset(self::$db))
        {
            self::$db = new DBConnection();
        }
        return self::$db->conn;
    }
}