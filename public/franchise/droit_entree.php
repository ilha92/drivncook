<?php
session_start();
require_once "../../config/database.php";

if ($_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST["payer"])) {
    $stmt = $pdo->prepare("
        UPDATE franchises 
        SET droit_entree = 'accepte'
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION["franchise_id"]]);

    // Met à jour la session
    $_SESSION["droit_entree"] = 'accepte';

    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Droit d'entrée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../../includes/navbar.php"; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow text-center">
                <div class="card-body p-5">

                    <h1 class="mb-4">Droit d'entrée</h1>

                    <p class="fs-5">
                        Pour accéder à l’ensemble de la plateforme <strong>Driv’n Cook</strong>,
                        vous devez régler le droit d’entrée.
                    </p>

                    <h2 class="text-success my-4">
                        50 000 €
                    </h2>

                    <div class="alert alert-warning">
                        Ceci est un <strong>paiement virtuel</strong> (projet pédagogique).
                    </div>

                    <form method="POST">
                        <button name="payer" class="btn btn-success btn-lg w-100">
                            Payer maintenant
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="../index.php" class="btn btn-outline-dark">
                    Retour
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
