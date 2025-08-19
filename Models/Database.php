<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct() {
        // Récupération des variables d'environnement Railway
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->db_name = getenv('DB_NAME') ?: "forum_db";
        $this->username = getenv('DB_USER') ?: "root";
        $this->password = getenv('DB_PASSWORD') ?: "";
        $this->port = getenv('DB_PORT') ?: "3306";
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            error_log("Erreur de connexion DB: " . $exception->getMessage());
            // Ne pas afficher l'erreur en production
            if (getenv('APP_ENV') === 'development') {
                echo "Erreur de connexion : " . $exception->getMessage();
            } else {
                echo "Erreur de connexion à la base de données";
            }
        }
        return $this->conn;
    }
}
?>