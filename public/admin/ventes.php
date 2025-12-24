<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Vente.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Récupérer toutes les ventes
$ventes = Vente::getAll($pdo);

// Calcul du CA par franchisé
$caParFranchise = [];

foreach ($ventes as $v) {
    $nom = $v["franchise"];
    if (!isset($caParFranchise[$nom])) {
        $caParFranchise[$nom] = 0;
    }
    $caParFranchise[$nom] += $v["montant"];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Ventes</title>
</head>
<body>

<h1>Analyse des ventes</h1>

<!-- ======================
     HISTORIQUE DES VENTES
====================== -->
<h2>Historique des ventes</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Franchisé</th>
    <th>Montant (€)</th>
</tr>

<?php foreach ($ventes as $v): ?>
<tr>
    <td><?= htmlspecialchars($v["date_vente"]) ?></td>
    <td><?= htmlspecialchars($v["franchise"]) ?></td>
    <td><?= number_format($v["montant"], 2, ',', ' ') ?> €</td>
</tr>
<?php endforeach; ?>
</table>

<br><hr><br>

<!-- ======================
     CA & REVERSEMENTS
====================== -->
<h2>Chiffre d'affaires & reversements (4%)</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Franchisé</th>
    <th>CA total (€)</th>
    <th>4% à reverser (€)</th>
</tr>

<?php foreach ($caParFranchise as $franchise => $ca): ?>
<tr>
    <td><?= htmlspecialchars($franchise) ?></td>
    <td><?= number_format($ca, 2, ',', ' ') ?> €</td>
    <td><?= number_format($ca * 0.04, 2, ',', ' ') ?> €</td>
</tr>
<?php endforeach; ?>
</table>

<br>
<a href="dashboard.php">⬅ Retour admin</a>

</body>
</html>
