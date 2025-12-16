<?php
session_start();

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>

<h1>Back-office Admin</h1>

<ul>
    <li><a href="franchises.php">Franchisés</a></li>
    <li><a href="ventes.php">Ventes</a></li>
    <li><a href="achats.php">Achats</a></li>
    <li><a href="camions.php">Camions</a></li>
</ul>

<a href="../logout.php">Se déconnecter</a>

</body>
</html>
