<?php
session_start();
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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard franchisé</title>
</head>
<body>

<h1>Mon Espace franchisé</h1>

<ul>
    <li><a href="profil.php">Mon profil</a></li>
    <li><a href="nouvel_achat.php">Nouvel achat</a></li>
    <li><a href="achat.php">Mes achats</a></li>
    <li><a href="ventes.php">Mes ventes</a></li>
    <li><a href="camions.php">Mes camions</a></li>
    <li><a href="commandes.php">Mes commandes</a></li>
    <li><a href="entretien.php?id=1">Carnet d'entretien (exemple camion ID 1)</a></li>
</ul>

<a href="../index.php">Retour à l'accueil </a>
<br>
<a href="../../access/logout.php">Se déconnecter</a>

</body>
</html>
