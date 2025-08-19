<?php
require_once "Database.php";

class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un nouvel utilisateur
    public function create($username, $email, $password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO {$this->table} (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        return $stmt->execute();
    }

    // Lire un utilisateur par email
    public function getByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifier mot de passe
    public function verifyPassword($email, $password) {
        $user = $this->getByEmail($email);
        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
