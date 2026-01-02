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
    <title>Dashboard Franchisé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<?php include "../../includes/navbar.php"; ?>

<div class="container py-5">

    <h1 class="text-center mb-4 fw-bold">Mon espace franchisé</h1>
    <p class="text-center text-muted mb-5">
        Gérez vos commandes, vos ventes et votre activité facilement
    </p>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Mon profil</h5>
                    <p class="card-text">Consulter et modifier vos informations personnelles.</p>
                    <a href="profil.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Nouvel achat</h5>
                    <p class="card-text">Commander des produits auprès du fournisseur.</p>
                    <a href="nouvel_achat.php" class="btn btn-primary w-100">Commander</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Mes commandes</h5>
                    <p class="card-text">Consulter l’historique de vos commandes.</p>
                    <a href="commandes.php" class="btn btn-primary w-100">Voir</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Mes ventes</h5>
                    <p class="card-text">Suivre vos ventes et performances.</p>
                    <a href="ventes.php" class="btn btn-primary w-100">Voir</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Mes camions</h5>
                    <p class="card-text">Gérer vos camions et emplacements.</p>
                    <a href="camions.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Entretien</h5>
                    <p class="card-text">Suivi et historique d’entretien.</p>
                    <a href="entretien.php?id=1" class="btn btn-primary w-100">Voir</a>
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-5">
        <a href="../../access/logout.php" class="btn btn-outline-danger">Se déconnecter</a>
    </div>

</div>
</body>
</html>

