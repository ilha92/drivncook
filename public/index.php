<?php
session_start();

// Si l'utilisateur n'est pas connecté
if (!isset($_SESSION["type"])) {
    header("Location: ../access/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Accueil</title>
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<h1>Accueil</h1>

<?php
if ($_SESSION["type"] === "admin") {
    echo "<p>Bienvenue Administrateur 👑</p>";
    echo "<a href=\"admin/franchises.php\">Gérer les franchisés</a><br>";
    echo "<a href=\"admin/dashboard.php\">Tableau de bord admin</a><br>";
}

if ($_SESSION["type"] === "franchise") {
    echo "<p>Bienvenue Franchisé 🚚</p>";
    echo "<a href=\"franchise/profil.php\">Mon profil</a><br>";
    echo "<a href=\"franchise/dashboard.php\">Tableau de bord franchise</a><br>";
}
?>

<a href="../../access/logout.php">Se déconnecter</a>
</body>
</html>