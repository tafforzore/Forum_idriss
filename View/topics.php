<?php
include('../includes/head.php');

require_once "../Models/Database.php";
require_once "../Models/Category.php";

$db = new Database();
$conn = $db->getConnection();
$categoryObj = new Category($conn);

// Récupérer toutes les catégories
$categories = $categoryObj->getAll()->fetchAll(PDO::FETCH_ASSOC);
?>


  <main>

  <!-- Sujets proposés / Catégories -->
<section class="categories-box">
  <h3>📌 Sujets proposés pour toi</h3>
  <div class="grid cols-2">
  <?php foreach($categories as $cat): ?>

    <a class="topic" href="topic.php?category_id=<?= $cat['id']?>&type=Général">
      <div>
        <strong> <?= htmlspecialchars($cat['name']) ?></strong>
        <div class="meta"> <?= htmlspecialchars($cat['commentaire']) ?></div>
      </div>
      <span class="badge">152 sujets</span>
    </a>
    <?php endforeach; ?>
    
  </div>
</section>
  </main>

<?php
include('../includes/footer.php');
?>