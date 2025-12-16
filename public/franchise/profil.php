<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

$franchise_id = $_SESSION["franchise_id"];

require_once "../../src/models/Franchise.php";

$franchise = Franchise::getById($pdo, $_SESSION["franchise_id"]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
</head>
<body>

<h1>Mon profil</h1>

<p><strong>Nom :</strong> <?= $franchise["nom"] ?></p>
<p><strong>Email :</strong> <?= $franchise["email"] ?></p>
<p><strong>Ville :</strong> <?= $franchise["ville"] ?></p>
<p><strong>Téléphone :</strong> <?= $franchise["telephone"] ?></p>

<a href="edit_profil.php">Modifier mes informations</a><br><br>
<a href="dashboard.php">Retour</a>

</body>
</html>
