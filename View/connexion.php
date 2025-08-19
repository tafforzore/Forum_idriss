<?php
session_start(); // Démarrer la session

include('../includes/head.php');

require_once "../Models/Database.php";
require_once "../Models/User.php";


$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Créer l'utilisateur
    if ($user->create($username, $email, $password)) {
        // Récupérer les informations complètes de l'utilisateur
        $currentUser = $user->getByEmail($email);

        // Stocker l'utilisateur dans la session
        $_SESSION['user'] = [
            'id' => $currentUser['id'],
            'username' => $currentUser['username'],
            'email' => $currentUser['email']
        ];

        // Redirection vers index.php
        header("Location: ../View/index.php");
        exit;
    } else {
        echo "<p>Erreur lors de l'inscription. L'email existe peut-être déjà.</p>";
    }
} else {
    echo "<p>Formulaire non soumis.</p>";
}
?>



<main>
    <h2>Inscription</h2>
    <form  method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">S'inscrire</button>
    </form>
</main> 
<?php
include('../includes/footer.php');
?>
