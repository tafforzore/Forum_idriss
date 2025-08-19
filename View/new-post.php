<?php

include('../includes/head.php');
?>

  <main>
    <h2>Créer un nouveau sujet</h2>
    <form method="post">
      <label for="title">Titre :</label>
      <input type="text" id="title" name="title">

      <label for="message">Message :</label>
      <textarea id="message" name="message"></textarea>

      <button type="submit">Publier</button>
    </form>
  </main>

<?php
include('../includes/footer.php');
?>

