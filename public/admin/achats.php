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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Achats</title>
</head>
<body>
<?php include "../../includes/navbar_admin.php"; ?>
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
<a href="dashboard.php">Retour</a>

</body>
</html>
