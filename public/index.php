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
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- ADMIN -->
    <?php if ($_SESSION["type"] === "admin"): ?>
        <?php include "../includes/navbar_admin.php"; ?>
        <div class="container mt-5">
            <h1 class="text-center mb-5">Accueil</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h3 class="mb-3">Bienvenue Administrateur</h3>

                        <div class="d-grid gap-3">
                            <a href="admin/franchises.php" class="btn btn-primary">
                                Gérer les franchisés
                            </a>
                            <a href="admin/dashboard.php" class="btn btn-dark">
                                Tableau de bord admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php if ($_SESSION["type"] === "franchise"): ?>
    <?php include "../includes/navbar.php"; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Accueil</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h3 class="mb-3">
                        Bienvenue <?= htmlspecialchars($_SESSION["nom"]) ?>
                    </h3>

                    <div class="d-grid gap-3">
                        <a href="franchise/profil.php" class="btn btn-success">
                            Mon profil
                        </a>
                        <a href="franchise/dashboard.php" class="btn btn-dark">
                            Tableau de bord franchise
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
    <!-- DECONNEXION -->
    <div class="text-center mt-5">
        <a href="../../access/logout.php" class="btn btn-outline-danger">
            🔒 Se déconnecter
        </a>
    </div>

</div>

</body>
</html>
