<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Approvisionnement.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../../access/login.php");
    exit;
}

$action = $_GET["action"] ?? null;

/* =======================
   SUPPRESSION
======================= */
if ($action === "delete" && isset($_GET["id"])) {
    Approvisionnement::delete($pdo, $_GET["id"]);
    header("Location: approvisionnements.php");
    exit;
}

/* =======================
   VALIDATION
======================= */
if ($action === "valider") {

    Approvisionnement::validerCommande(
        $pdo,
        $_GET["commande_id"],
        $_GET["produit_id"],
        $_GET["quantite"]
    );

    header("Location: approvisionnements.php");
    exit;
}

// Liste des commandes
$commandes = Approvisionnement::getAllCommandes($pdo);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Approvisionnements</title>
</head>
<body>

<h1>Gestion des commandes</h1>

<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Franchisé</th>
    <th>Produit</th>
    <th>Stock</th>
    <th>Quantité</th>
    <th>Statut</th>
    <th>Action</th>
</tr>

<?php foreach ($commandes as $c): ?>
<tr>
    <td><?= $c["date_commande"] ?></td>
    <td><?= $c["franchise"] ?></td>
    <td><?= $c["produit"] ?></td>
    <td><?= $c["stock"] ?></td>
    <td><?= $c["quantite"] ?></td>
    <td><?= $c["statut"] ?></td>
    <td>

        <?php if ($c["statut"] === "en attente"): ?>

            <a href="approvisionnements.php?action=valider&commande_id=<?= $c['id'] ?>&produit_id=<?= $c['produit_id'] ?>&quantite=<?= $c['quantite'] ?>"
               onclick="return confirm('Valider cette commande ?')">
                ✅ Valider
            </a>

            |
            <a href="approvisionnements.php?action=delete&id=<?= $c['id'] ?>"
               onclick="return confirm('Supprimer cette commande ?')">
                🗑️ Supprimer
            </a>

        <?php else: ?>

            ✔️ Validée |
            <a href="approvisionnements.php?action=delete&id=<?= $c['id'] ?>"
               onclick="return confirm('Supprimer cette commande ?')">
                🗑️ Supprimer
            </a>

        <?php endif; ?>

    </td>
</tr>

<?php endforeach; ?>
</table>

<a href="dashboard.php">⬅ Retour admin</a>

</body>
</html>
