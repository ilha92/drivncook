<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Admin.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$achats = Admin::getAllAchats($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Achats</title>
</head>
<body>

<h1>Achats des franchisés</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>Date</th>
        <th>Franchisé</th>
        <th>Entrepôt</th>
        <th>Montant (€)</th>
    </tr>

    <?php foreach ($achats as $achat): ?>
        <tr>
            <td><?= $achat["date_achat"] ?></td>
            <td><?= $achat["nom"] ?></td>
            <td><?= $achat["entrepot"] ?></td>
            <td><?= $achat["montant_total"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="dashboard.php">⬅ Retour</a>

</body>
</html>
