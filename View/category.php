<?php
require_once "../Models/Database.php";
require_once "../Models/Category.php";

// Traitement du formulaire
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $db = new Database();
        $conn = $db->getConnection();
        $category = new Category($conn);
        if ($category->create($name)) {
            $message = "Catégorie '$name' ajoutée avec succès !";
        } else {
            $message = "Erreur lors de l'ajout de la catégorie.";
        }
    } else {
        $message = "Le nom de la catégorie ne peut pas être vide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une catégorie</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 30px; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type=text] { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #ccc; }
        button { padding: 10px 15px; border: none; border-radius: 4px; background-color: #4CAF50; color: white; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .message { text-align: center; margin-bottom: 15px; color: green; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Ajouter une nouvelle catégorie</h2>

    <?php if(!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="Nom de la catégorie" required>
        <button type="submit">Ajouter</button>
    </form>
<?php
include('../includes/footer.php');
?>