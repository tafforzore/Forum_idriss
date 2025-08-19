<?php
class Category {
    private $conn;
    private $table = "categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Récupérer toutes les catégories
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt;
    }

    // Récupérer une catégorie par id
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les topics liés à une catégorie
    public function getByCategory($category_id) {
        $stmt = $this->conn->prepare("
            SELECT t.*, u.username 
            FROM topics t
            JOIN users u ON t.created_by = u.id
            WHERE t.category_id = :cat_id
            ORDER BY t.created_at DESC
        ");
        $stmt->execute([':cat_id' => $category_id]);
        return $stmt;
    }

    // Créer un nouveau topic dans cette catégorie
    public function createTopic($category_id, $title, $description, $user_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO topics (category_id, title, description, created_by) 
            VALUES (:cat_id, :title, :desc, :user_id)
        ");
        return $stmt->execute([
            ':cat_id' => $category_id,
            ':title' => $title,
            ':desc' => $description,
            ':user_id' => $user_id
        ]);
    }

    // Récupérer un topic précis
    public function getTopicById($topic_id) {
        $stmt = $this->conn->prepare("
            SELECT t.*, u.username 
            FROM topics t
            JOIN users u ON t.created_by = u.id
            WHERE t.id = :topic_id
            LIMIT 1
        ");
        $stmt->execute([':topic_id' => $topic_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CRUD classique pour la catégorie
    public function create($name, $commentaire = null) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, commentaire) VALUES (:name, :commentaire)");
        return $stmt->execute([
            ':name' => $name,
            ':commentaire' => $commentaire
        ]);
    }

    public function update($id, $name, $commentaire = null) {
        $stmt = $this->conn->prepare("UPDATE categories SET name = :name, commentaire = :commentaire WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':commentaire' => $commentaire
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
