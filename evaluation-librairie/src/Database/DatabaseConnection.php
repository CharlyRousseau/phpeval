<?php
namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;

class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;
    private ?PDO $connection = null;

    private string $host;
    private string $db;
    private string $user;
    private string $password;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        // Retrieve database credentials from environment variables
        $this->host = getenv("DB_HOST");
        $this->db = getenv("DB_NAME");
        $this->user = getenv("DB_USER");
        $this->password = getenv("DB_PASSWORD");

        $this->connect();
    }

    /**
     * Get the single instance of the DatabaseConnection.
     * @return DatabaseConnection
     */
    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    /**
     * Connect to the database.
     * @throws RuntimeException If the connection fails, throws an exception with the error message.
     */
    private function connect(): void
    {
        try {
            // Construct the DSN for the PDO connection
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8";
            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Database connection error: " . $e->getMessage()
            );
        }
    }

    /**
     * Get the PDO connection instance.
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Prevent cloning of the instance.
     */
    private function __clone(): void
    {
    }

    /**
     * Destructor to close the connection when the instance is destroyed.
     */
    public function __destruct()
    {
        $this->connection = null;
    }
}
?>
