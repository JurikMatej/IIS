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


    // TODO Create env variable system to store secrets in

    /**
     * @const string
     */
    private const DB_DSN = 'mysql:host=localhost;dbname=auctionsystem'; // Testing phase only

    /**
     * @var string
     */
    private $db_user = "root"; // Testing phase only

    /**
     * @var string
     */
    private $db_pass = ""; // Testing phase only


    /**
     * DBConnection constructor defined explicitly as private to prevent instantiating this class
     *
     * @brief Create database connection
     */
    private final function __construct()
    {
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


    private function establishConnection()
    {
        $this->conn = new PDO(self::DB_DSN, $this->db_user, $this->db_pass);
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