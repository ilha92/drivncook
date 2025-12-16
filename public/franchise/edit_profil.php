<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Franchise.php";


if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

$franchise_id = $_SESSION["franchise_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $ville = $_POST["ville"];
    $telephone = $_POST["telephone"];

    Franchise::updateProfil($pdo, $ville, $telephone, $franchise_id);


    header("Location: profil.php");
    exit;
}
// Récupération des infos
$franchise = Franchise::getById($pdo, $franchise_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
</head>
<body>

<h1>Modifier mon profil</h1>

<form method="POST">
    <label>Email :</label><br>
    <input type="email" name="email" value="<?= $franchise["email"] ?>"><br><br>

    <label>Ville :</label><br>
    <input type="text" name="ville" value="<?= $franchise["ville"] ?>"><br><br>

    <label>Téléphone :</label><br>
    <input type="text" name="telephone" value="<?= $franchise["telephone"] ?>"><br><br>

    <button type="submit">Enregistrer</button>
</form>

<a href="profil.php">Annuler</a>

</body>
</html>
