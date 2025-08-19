<?php

class Topic {
    private $conn;
    private $table = "topics";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($title, $description, $user_id) {
        $sql = "INSERT INTO ".$this->table." (title, description, created_by) 
                VALUES (:title, :description, :created_by)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":title" => $title,
            ":description" => $description,
            ":created_by" => $user_id
        ]);
    }

    public function getAll() {
        $sql = "SELECT t.*, u.username FROM ".$this->table." t 
                LEFT JOIN users u ON t.created_by = u.id 
                ORDER BY t.created_at DESC";
        return $this->conn->query($sql);
    }
}

?>
