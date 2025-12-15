<?php
// public/camion_edit.php — create/edit form
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/CamionController.php';
$editing = false;
$camion = null;
if (!empty($_GET['id'])) {
    $editing = true;
    require_once __DIR__ . '/../src/models/Camion.php';
    $camion = Camion::find($_GET['id']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        CamionController::update($_POST['id'], $_POST);
        header('Location: /public/camions.php');
        exit;
    } else {
        CamionController::create($_POST);
        header('Location: /public/camions.php');
        exit;
    }
}
if (!empty($_GET['delete'])) {
    CamionController::delete($_GET['delete']);
    header('Location: /public/camions.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title><?= $editing ? 'Modifier' : 'Créer' ?> un camion</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <h1><?= $editing ? 'Modifier' : 'Créer' ?> un camion</h1>
  <form method="post" action="/public/camion_edit.php">
    <?php if ($editing): ?>
      <input type="hidden" name="id" value="<?=htmlspecialchars($camion['id'])?>">
    <?php endif; ?>
    <label>Numéro camion: <input type="text" name="numero_camion" value="<?= $editing ? htmlspecialchars($camion['numero_camion']) : '' ?>" required></label>
    <label>État: <select name="etat"><option value="actif" <?= $editing && $camion['etat']==='actif' ? 'selected' : '' ?>>Actif</option><option value="panne" <?= $editing && $camion['etat']==='panne' ? 'selected' : '' ?>>Panne</option><option value="maintenance" <?= $editing && $camion['etat']==='maintenance' ? 'selected' : '' ?>>Maintenance</option></select></label>
    <label>Emplacement: <input type="text" name="emplacement" value="<?= $editing ? htmlspecialchars($camion['emplacement']) : '' ?>"></label>
    <label>Franchise ID: <input type="number" name="franchise_id" value="<?= $editing ? htmlspecialchars($camion['franchise_id']) : '' ?>"></label>
    <button type="submit"><?= $editing ? 'Enregistrer' : 'Créer' ?></button>
  </form>
  <p><a href="/public/camions.php">Retour</a></p>
</body>
</html>
