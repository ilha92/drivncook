<?php
// public/login.php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $result = AuthController::login($_POST['email'] ?? '', $_POST['password'] ?? '');
  if ($result['success']) {
    // Redirection simple selon le rôle
    if ($result['user']['role'] === 'admin') {
      header('Location: /public/admin.php');
    } else {
      header('Location: /src/views/franchise/index.php');
    }
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
  <title>Connexion - Driv'n Cook</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <h1>Connexion</h1>
  <?php if (!empty($message)): ?>
    <p style="color:red"><?=htmlspecialchars($message)?></p>
  <?php endif; ?>
  <?php if (!empty($_GET['registered'])): ?>
    <p style="color:green">Inscription réussie — veuillez vous connecter.</p>
  <?php endif; ?>
  <form method="post" action="/public/login.php">
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Mot de passe: <input type="password" name="password" required></label><br>
    <button type="submit">Se connecter</button>
  </form>
  <p><a href="/public/register.php">Créer un compte franchisé</a></p>
</body>
</html>
