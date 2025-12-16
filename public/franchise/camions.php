<?php
session_start();
require_once "../../config/database.php";

// Sécurité
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

$franchise_id = $_SESSION["franchise_id"];

$sql = "SELECT * FROM camions WHERE franchise_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$franchise_id]);
$camions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes camions</title>
</head>
<body>

<h1>Mes camions</h1>

<?php if (count($camions) == 0): ?>
    <p>Aucun camion attribué.</p>
<?php else: ?>

<table border="1" cellpadding="5">
    <tr>
        <th>Immatriculation</th>
        <th>Modèle</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($camions as $camion): ?>
        <tr>
            <td><?= $camion["immatriculation"] ?></td>
            <td><?= $camion["modele"] ?></td>
            <td><?= $camion["statut"] ?></td>
            <td>
                <a href="panne.php?id=<?= $camion["id"] ?>">Déclarer panne</a> |
                <a href="entretien.php?id=<?= $camion["id"] ?>">Carnet d'entretien</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php endif; ?>

<br>
<a href="dashboard.php">⬅ Retour</a>

</body>
</html>
