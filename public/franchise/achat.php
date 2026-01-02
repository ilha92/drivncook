<?php
session_start();
require_once "../../config/database.php";

// Sécurité : uniquement franchisé
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

// Vérification droit d'entrée
if ($_SESSION["droit_entree"] !== "accepte") {
    header("Location: droit_entree.php");
    exit;
}

$franchise_id = $_SESSION["franchise_id"];

// Récupérer les achats du franchisé
$sql = "
SELECT 
    achats.date_achat,
    achats.montant_total,
    achats.pourcentage_entrepot,
    entrepots.nom AS nom_entrepot
FROM achats
LEFT JOIN entrepots ON achats.entrepot_id = entrepots.id
WHERE achats.franchise_id = ?
ORDER BY achats.date_achat DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$franchise_id]);
$achats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mes achats</title>
</head>
<body>
<?php include "../../includes/navbar.php"; ?>
<h1>Mes achats</h1>

<?php if (count($achats) == 0): ?>
    <p>Aucun achat enregistré.</p>
<?php else: ?>

<table border="1" cellpadding="5">
    <tr>
        <th>Date</th>
        <th>Entrepôt</th>
        <th>Montant (€)</th>
        <th>% Entrepôt</th>
    </tr>

    <?php foreach ($achats as $achat): ?>
        <tr>
            <td><?= $achat["date_achat"] ?></td>
            <td><?= $achat["nom_entrepot"] ?></td>
            <td><?= $achat["montant_total"] ?></td>
            <td><?= $achat["pourcentage_entrepot"] ?> %</td>
        </tr>
    <?php endforeach; ?>
</table>

<?php endif; ?>

<br>
<a href="dashboard.php">⬅ Retour au dashboard</a>

</body>
</html>
