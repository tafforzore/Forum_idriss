<?php
session_start();
require_once "../Models/Database.php";
require_once "../Models/Post.php";
require_once "../Models/Category.php";

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    die("Vous devez être connecté !");
}

$db = new Database();
$conn = $db->getConnection();
$postObj = new Post($conn);
$categoryObj = new Category($conn);

// Récupérer toutes les catégories
$categories = $categoryObj->getAll()->fetchAll(PDO::FETCH_ASSOC);

// Catégorie sélectionnée
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Topic sélectionné
$selected_topic_id = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : 0;

// Créer un nouveau post si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $selected_topic_id) {
    $message = trim($_POST['message']);
    $user_id = (int)$_SESSION['user']['id'];
    if (!empty($message)) {
        $postObj->create($selected_topic_id, $user_id, $message);
        header("Location: topic.php?category_id={$category_id}&topic_id={$selected_topic_id}");
        exit;
    }
}

// Créer un nouveau topic si formulaire création soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['description'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $user_id = (int)$_SESSION['user']['id'];
    if (!empty($title) && !empty($description) && $category_id) {
        $categoryObj->createTopic($category_id, $title, $description, $user_id);
        $selected_topic_id = $conn->lastInsertId();
        header("Location: topic.php?category_id={$category_id}&topic_id={$selected_topic_id}");
        exit;
    }
}

// Récupérer les topics pour la catégorie sélectionnée
$topics = $category_id ? $categoryObj->getByCategory($category_id)->fetchAll(PDO::FETCH_ASSOC) : [];

include('../includes/head.php');
?>

<main class="forum-layout">
  <!-- Colonne gauche -->
  <section class="left-panel">
      <h2>Catégorie : <?= $category_id ? htmlspecialchars($categoryObj->getById($category_id)['name']) : 'Aucune sélection' ?></h2>
      <p class="user-info">Connecté en tant que : <strong><?= $_SESSION['user']['username'] ?></strong></p>

      <?php if (!$selected_topic_id): ?>
          <!-- Liste des topics -->
          <div class="topics-list">
              <h3>📌 Choisissez un sujet ou créez-en un nouveau</h3>
              <ul>
                  <?php foreach($topics as $topic): ?>
                      <li>
                          <a href="topic.php?category_id=<?= $category_id ?>&topic_id=<?= $topic['id'] ?>">
                              <?= htmlspecialchars($topic['title']) ?>
                          </a>
                      </li>
                  <?php endforeach; ?>
              </ul>
          </div>

          <!-- Formulaire création de topic -->
          <div class="add-topic">
              <h3>➕ Créer un nouveau sujet</h3>
              <form method="post">
                  <input type="text" name="title" placeholder="Titre du sujet" required>
                  <textarea name="description" rows="2" placeholder="Description rapide..." required></textarea>
                  <button type="submit">Créer</button>
              </form>
          </div>
      <?php else: ?>
          <!-- Fil de discussion du topic sélectionné -->
          <?php
          $posts = $postObj->readByTopic($selected_topic_id)->fetchAll(PDO::FETCH_ASSOC);
          $topic_data = $categoryObj->getTopicById($selected_topic_id);
          ?>
          <h3>Sujet : <?= htmlspecialchars($topic_data['title']) ?></h3>
          <p>Description : <?= htmlspecialchars($topic_data['description']) ?></p>

          <div class="posts-box">
              <?php foreach($posts as $p): ?>
                  <div class="post">
                      <span class="msg-id">#<?= $p['id'] ?></span>
                      <strong><?= htmlspecialchars($p['username']) ?></strong>
                      <span class="date"><?= $p['created_at'] ?></span>
                      <p><?= nl2br(htmlspecialchars($p['message'])) ?></p>
                  </div>
              <?php endforeach; ?>
          </div>

          <!-- Réponse au topic -->
          <div class="add-post">
              <h3>💭 Répondre</h3>
              <form method="post">
                  <textarea name="message" rows="4" placeholder="Écrivez votre message..." required></textarea><br>
                  <button type="submit">Envoyer</button>
              </form>
          </div>
      <?php endif; ?>
  </section>

  <!-- Colonne droite -->
  <aside class="right-panel">
      <h3>📌 Liste des catégories</h3>
      <ul>
          <?php foreach($categories as $cat): ?>
              <li>
                  <a href="topic.php?category_id=<?= $cat['id'] ?>">
                      <?= htmlspecialchars($cat['name']) ?>
                  </a>
                  <?php if(!empty($cat['commentaire'])): ?>
                      <p class="category-comment"><?= htmlspecialchars($cat['commentaire']) ?></p>
                  <?php endif; ?>
              </li>
          <?php endforeach; ?>
      </ul>
  </aside>
</main>

<?php
include('../includes/footer.php');
?>
