<?php
// public/franchises.php — page de test pour lister/créer franchises
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/FranchiseController.php';
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $id = FranchiseController::create($_POST);
    if ($id) header('Location: /public/franchises.php');
}
if (!empty($_GET['delete'])) {
  FranchiseController::delete($_GET['delete']);
  header('Location: /public/franchises.php');
  exit;
}
$franchises = FranchiseController::index();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Franchises — Driv'n Cook</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <h1>Franchises</h1>
  <p><a href="/public/franchise_edit.php">Créer une franchise</a></p>
  <?php if (!empty($message)) echo '<p style="color:red">'.htmlspecialchars($message).'</p>'; ?>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>User ID</th><th>Ville</th><th>Téléphone</th><th>CA</th><th>Actions</th></tr>
    <?php foreach ($franchises as $f): ?>
    <tr>
      <td><?=htmlspecialchars($f['id'])?></td>
      <td><?=htmlspecialchars($f['user_id'])?></td>
      <td><?=htmlspecialchars($f['ville'])?></td>
      <td><?=htmlspecialchars($f['telephone'])?></td>
      <td><?=htmlspecialchars($f['chiffre_affaires'])?></td>
      <td>
        <a href="/public/franchise_edit.php?id=<?=htmlspecialchars($f['id'])?>">Modifier</a>
        <a href="/public/franchises.php?delete=<?=htmlspecialchars($f['id'])?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
