<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Camion.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$camions = Camion::getAll($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Camions</title>
</head>
<body>

<h1>Parc de camions</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>Immatriculation</th>
        <th>Modèle</th>
        <th>Statut</th>
        <th>Franchisé</th>
    </tr>

    <?php foreach ($camions as $camion): ?>
        <tr>
            <td><?= $camion["immatriculation"] ?></td>
            <td><?= $camion["modele"] ?></td>
            <td><?= $camion["statut"] ?></td>
            <td><?= $camion["nom"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="dashboard.php">⬅ Retour</a>

</body>
</html>
