<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Commande.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

$action = $_GET["action"] ?? "list";

/* =======================
   PASSER COMMANDE
======================= */
if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
    Commande::create(
        $pdo,
        $_SESSION["franchise_id"],
        $_POST["produit_id"],
        $_POST["quantite"]
    );
    header("Location: commandes.php");
    exit;
}

$produits = Commande::getProduits($pdo);
$commandes = Commande::getByFranchise($pdo, $_SESSION["franchise_id"]);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mes commandes</title>
</head>
<body>

<h1>Mes commandes</h1>

<h2>Passer une commande</h2>

<form method="POST" action="?action=add">
    <label>Produit</label><br>
    <select name="produit_id" required>
        <?php foreach ($produits as $p): ?>
            <option value="<?= $p["id"] ?>">
                <?= $p["nom"] ?> (stock : <?= $p["stock"] ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Quantité</label><br>
    <input type="number" name="quantite" min="1" required><br><br>

    <button>Commander</button>
</form>

<hr>

<!-- =======================
     HISTORIQUE
======================= -->
<h2>Historique de mes commandes</h2>

<?php if (count($commandes) === 0): ?>
    <p>Aucune commande.</p>
<?php else: ?>
<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Produit</th>
    <th>Quantité</th>
    <th>Statut</th>
</tr>

<?php foreach ($commandes as $c): ?>
<tr>
    <td><?= $c["date_commande"] ?></td>
    <td><?= $c["produit"] ?></td>
    <td><?= $c["quantite"] ?></td>
    <td><?= $c["statut"] ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<a href="dashboard.php">⬅ Retour dashboard</a>

</body>
</html>
