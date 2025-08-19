<?php
class Post {
    private $conn;
    private $table = "posts";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un nouveau post
    public function create($topic_id, $user_id, $message) {
        $stmt = $this->conn->prepare("
            INSERT INTO posts (topic_id, user_id, message) 
            VALUES (:topic_id, :user_id, :message)
        ");
        return $stmt->execute([
            ':topic_id' => $topic_id,
            ':user_id' => $user_id,
            ':message' => $message
        ]);
    }

    // Récupérer tous les posts d'un topic
    public function readByTopic($topic_id) {
        $stmt = $this->conn->prepare("
            SELECT p.*, u.username 
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.topic_id = :topic_id
            ORDER BY p.created_at ASC
        ");
        $stmt->execute([':topic_id' => $topic_id]);
        return $stmt;
    }

    // Supprimer un post
    public function delete($post_id) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute([':id' => $post_id]);
    }

    // Mettre à jour un post
    public function update($post_id, $message) {
        $stmt = $this->conn->prepare("UPDATE posts SET message = :message WHERE id = :id");
        return $stmt->execute([':id' => $post_id, ':message' => $message]);
    }

    // Récupérer un post précis
    public function getById($post_id) {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
