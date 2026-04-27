<?php
/**
 * db_connect.php
 * Connexion PDO à la base de données - Pattern Singleton
 */
class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private string $dsn    = 'mysql:host=localhost;dbname=bd_agence_voyage;charset=utf8mb4';
    private string $user   = 'root';
    private string $psw    = '';

    private function __construct() {
        try {
            $this->connection = new PDO($this->dsn, $this->user, $this->psw);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}

// Compatibilité : variable $db disponible directement
$db = Database::getInstance()->getConnection();