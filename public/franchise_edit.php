<?php
// public/franchise_edit.php — create/edit form
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/FranchiseController.php';
$message = null;
$editing = false;
$franchise = null;
if (!empty($_GET['id'])) {
    $editing = true;
    require_once __DIR__ . '/../src/models/Franchise.php';
    $franchise = Franchise::find($_GET['id']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        FranchiseController::update($_POST['id'], $_POST);
        header('Location: /public/franchises.php');
        exit;
    } else {
        FranchiseController::create($_POST);
        header('Location: /public/franchises.php');
        exit;
    }
}
if (!empty($_GET['delete'])) {
    FranchiseController::delete($_GET['delete']);
    header('Location: /public/franchises.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title><?= $editing ? 'Modifier' : 'Créer' ?> une franchise</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <h1><?= $editing ? 'Modifier' : 'Créer' ?> une franchise</h1>
  <form method="post" action="/public/franchise_edit.php">
    <?php if ($editing): ?>
      <input type="hidden" name="id" value="<?=htmlspecialchars($franchise['id'])?>">
    <?php endif; ?>
    <label>User ID: <input type="number" name="user_id" value="<?= $editing ? htmlspecialchars($franchise['user_id']) : '' ?>" required></label>
    <label>Ville: <input type="text" name="ville" value="<?= $editing ? htmlspecialchars($franchise['ville']) : '' ?>"></label>
    <label>Téléphone: <input type="text" name="telephone" value="<?= $editing ? htmlspecialchars($franchise['telephone']) : '' ?>"></label>
    <label>Date entrée: <input type="date" name="date_entree" value="<?= $editing ? htmlspecialchars($franchise['date_entree']) : '' ?>"></label>
    <label>Chiffre affaires: <input type="number" step="0.01" name="chiffre_affaires" value="<?= $editing ? htmlspecialchars($franchise['chiffre_affaires']) : '0' ?>"></label>
    <button type="submit"><?= $editing ? 'Enregistrer' : 'Créer' ?></button>
  </form>
  <p><a href="/public/franchises.php">Retour</a></p>
</body>
</html>
