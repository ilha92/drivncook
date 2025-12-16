<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Admin.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$ventes = Admin::getAllVentes($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ventes</title>
</head>
<body>

<h1>Ventes des franchisés</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>Date</th>
        <th>Franchisé</th>
        <th>Montant (€)</th>
    </tr>

    <?php foreach ($ventes as $vente): ?>
        <tr>
            <td><?= $vente["date_vente"] ?></td>
            <td><?= $vente["nom"] ?></td>
            <td><?= $vente["montant"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="dashboard.php">⬅ Retour</a>

</body>
</html>
