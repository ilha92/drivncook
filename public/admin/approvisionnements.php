<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Approvisionnement.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$commandes = Approvisionnement::getAllCommandes($pdo);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Approvisionnements</title>
</head>
<body>

<h1>Commandes des franchisés</h1>

<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Franchisé</th>
    <th>Produit</th>
    <th>Quantité</th>
    <th>Statut</th>
</tr>

<?php foreach ($commandes as $c): ?>
<tr>
    <td><?= $c["date_commande"] ?></td>
    <td><?= $c["franchise"] ?></td>
    <td><?= $c["produit"] ?></td>
    <td><?= $c["quantite"] ?></td>
    <td><?= $c["statut"] ?></td>
</tr>
<?php endforeach; ?>
</table>

<a href="dashboard.php">⬅ Retour admin</a>

</body>
</html>
