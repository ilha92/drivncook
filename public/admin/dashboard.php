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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard Admin</title>
</head>
<body>
<?php include "../../includes/navbar_admin.php"; ?>
<h1>Back-office Admin</h1>

<ul>
    <li><a href="franchises.php">Franchisés</a></li>
    <li><a href="produits.php">Nouveaux Produits</a></li>
    <li><a href="approvisionnements.php">Approvisionnements</a></li>
    <li><a href="ventes.php">Ventes</a></li>
    <li><a href="achats.php">Achats</a></li>
    <li><a href="camions.php">Camions</a></li>
</ul>

<a href="../../access/logout.php">Se déconnecter</a>

</body>
</html>
