<?php
session_start();
require_once "../../config/database.php";

// Sécurité
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

$franchise_id = $_SESSION["franchise_id"];

// Récupérer les ventes
$sql = "
SELECT date_vente, montant
FROM ventes
WHERE franchise_id = ?
ORDER BY date_vente DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$franchise_id]);
$ventes = $stmt->fetchAll();

// Calcul du total
$total = 0;
foreach ($ventes as $vente) {
    $total += $vente["montant"];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes ventes</title>
</head>
<body>

<h1>Mes ventes</h1>

<p><strong>Total des ventes :</strong> <?= $total ?> €</p>

<?php if (count($ventes) == 0): ?>
    <p>Aucune vente enregistrée.</p>
<?php else: ?>

<table border="1" cellpadding="5">
    <tr>
        <th>Date</th>
        <th>Montant (€)</th>
    </tr>

    <?php foreach ($ventes as $vente): ?>
        <tr>
            <td><?= $vente["date_vente"] ?></td>
            <td><?= $vente["montant"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php endif; ?>

<br>
<a href="dashboard.php">⬅ Retour au dashboard</a>

</body>
</html>
