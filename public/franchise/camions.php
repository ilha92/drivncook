<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Camion.php";

// Sécurité : uniquement franchisé
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

// Vérification droit d'entrée du franchisé
if ($_SESSION["droit_entree"] !== "accepte") {
    header("Location: droit_entree.php");
    exit;
}


$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;

$camions = Camion::getByFranchise($pdo, $_SESSION["franchise_id"]);

if ($action === "panne" && $_SERVER["REQUEST_METHOD"] === "POST") {
    Camion::addPanne(
        $pdo,
        $_POST["camion_id"],
        $_POST["date_panne"],
        $_POST["type_panne"],
        $_POST["description"]
    );
    header("Location: camions.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Mes camions</title></head>
<body>
<?php include "../../includes/navbar.php"; ?>
<h1>Mes camions</h1>

<table border="1">
<tr>
    <th>Immatriculation</th>
    <th>Modèle</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>

<?php foreach ($camions as $c): ?>
<tr>
    <td><?= $c["immatriculation"] ?></td>
    <td><?= $c["modele"] ?></td>
    <td><?= $c["statut"] ?></td>
    <td>
        <a href="?action=panne&id=<?= $c['id'] ?>">🚨 Déclarer panne</a>
        <a href="?action=historique&id=<?= $c['id'] ?>">📜 Historique</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<a href="dashboard.php">Dashboard Franchise</a>
<?php if ($action === "panne" && $id): ?>
<h2>Déclarer une panne</h2>
<form method="POST">
    <input type="hidden" name="camion_id" value="<?= $id ?>">
    <input type="date" name="date_panne" required><br><br>
    <input name="type_panne" placeholder="Type de panne" required><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>
    <button>Envoyer</button>
</form>
<a href="camions.php">retour</a>
<?php endif; ?>
<?php if ($action === "historique" && $id): 
$pannes = Camion::getPannes($pdo, $id);
?>

<h2>Historique des pannes</h2>

<?php if (count($pannes) === 0): ?>
    <p>Aucune panne enregistrée.</p>
<?php else: ?>
<table border="1">
<tr>
    <th>Date</th>
    <th>Description</th>
</tr>

<?php foreach ($pannes as $p): ?>
<tr>
    <td><?= $p["date_panne"] ?></td>
    <td><?= htmlspecialchars($p["description"]) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<a href="camions.php">⬅ Retour</a>

<?php endif; ?>

</body>
</html>
