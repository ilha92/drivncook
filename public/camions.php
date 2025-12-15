<?php
// public/camions.php — page de test pour lister/créer camions
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/CamionController.php';
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    CamionController::create($_POST);
    header('Location: /public/camions.php');
    exit;
}
if (!empty($_GET['delete'])) {
  CamionController::delete($_GET['delete']);
  header('Location: /public/camions.php');
  exit;
}
$camions = CamionController::index();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Camions — Driv'n Cook</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <h1>Camions</h1>
  <p><a href="/public/camion_edit.php">Créer un camion</a></p>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Numéro</th><th>État</th><th>Emplacement</th><th>Franchise</th><th>Actions</th></tr>
    <?php foreach ($camions as $c): ?>
    <tr>
      <td><?=htmlspecialchars($c['id'])?></td>
      <td><?=htmlspecialchars($c['numero_camion'])?></td>
      <td><?=htmlspecialchars($c['etat'])?></td>
      <td><?=htmlspecialchars($c['emplacement'])?></td>
      <td><?=htmlspecialchars($c['franchise_id'])?></td>
      <td>
        <a href="/public/camion_edit.php?id=<?=htmlspecialchars($c['id'])?>">Modifier</a>
        <a href="/public/camions.php?delete=<?=htmlspecialchars($c['id'])?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
