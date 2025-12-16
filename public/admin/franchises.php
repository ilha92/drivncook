<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Admin.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$franchises = Admin::getAllFranchises($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Franchisés</title>
</head>
<body>

<h1>Liste des franchisés</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Ville</th>
        <th>Téléphone</th>
    </tr>

    <?php foreach ($franchises as $f): ?>
        <tr>
            <td><?= $f["nom"] ?></td>
            <td><?= $f["email"] ?></td>
            <td><?= $f["ville"] ?></td>
            <td><?= $f["telephone"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="dashboard.php">⬅ Retour</a>

</body>
</html>
