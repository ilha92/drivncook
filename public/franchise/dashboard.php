<?php
session_start();
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard franchisé</title>
</head>
<body>

<h1>Mon Espace franchisé [<?= $franchise["nom"] ?>]</h1>

<ul>
    <li><a href="profil.php">Mon profil</a></li>
    <li><a href="nouvel_achat.php">Nouvel achat</a></li>
    <li><a href="achat.php">Mes achats</a></li>
</ul>

<a href="../logout.php">Se déconnecter</a>

</body>
</html>
