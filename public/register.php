<?php
// public/register.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $result = AuthController::register($_POST);
  if ($result['success']) {
    header('Location: /public/login.php?registered=1');
    exit;
  } else {
    $message = $result['message'];
  }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Inscription - Driv'n Cook</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <h1>Inscription franchisé</h1>
  <?php if (!empty($message)): ?>
    <p style="color:red"><?=htmlspecialchars($message)?></p>
  <?php endif; ?>
  <form method="post" action="/public/register.php">
    <label>Nom complet: <input type="text" name="name" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Mot de passe: <input type="password" name="password" required></label><br>
    <button type="submit">S'inscrire</button>
  </form>
  <p><a href="/public/login.php">Déjà inscrit ? Se connecter</a></p>
</body>
</html>
