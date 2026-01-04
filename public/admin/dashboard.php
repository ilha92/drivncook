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

<h1 class="text-center mb-4 fw-bold">Back-office Admin</h1>
<p class="text-center mb-5">Gérer les différents aspects de l'entreprise</p>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Gerer les Franchisés</h5>
                    <p class="card-text">Consulter et modifier les informations des franchisés.</p>
                    <a href="franchises.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Nouveaux Produits</h5>
                    <p class="card-text">Créer de nouveaux produits.</p>
                    <a href="produits.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Approvisionnements</h5>
                    <p class="card-text">Gérer les approvisionnements des produits.</p>
                    <a href="approvisionnements.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Gerer les ventes</h5>
                    <p class="card-text">Mes ventes et performances.</p>
                    <a href="ventes.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Gerer les camions</h5>
                    <p class="card-text">Créer et gérer vos camions.</p>
                    <a href="camions.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <h5 class="card-title">Gerer les entrepôts</h5>
                    <p class="card-text">Gerer les entrepôts et leurs informations.</p>
                    <a href="entrepots.php" class="btn btn-primary w-100">Accéder</a>
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-5">
        <a href="../../access/logout.php" class="btn btn-outline-danger">Se déconnecter</a>
    </div>
</body>
</html>
