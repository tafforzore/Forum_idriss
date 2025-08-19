<?php 
session_start();

include('../includes/head.php');

?>

  <main>
    <h2>Mon Profil</h2>
    
    <?php
      if ($_SESSION['user']){
        ?>
    <p><strong>Nom :</strong><?=$_SESSION['user']['username']?></p>
    <p><strong>Email :</strong><?=$_SESSION['user']['email'] ?> </p>
     <?php }else{
      "
        <p><strong>Nom :</strong> Étudiant Test</p>
        <p><strong>Email :</strong> etudiant@test.com</p>
      ";
    }
    
    ?>
    
        <p><strong>Membre depuis :</strong> Janvier 2025</p>
  </main>

<?php
include('../includes/footer.php');
?>