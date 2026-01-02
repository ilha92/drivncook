<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Vente.php";
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


// Ajout d'une vente
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Vente::add(
        $pdo,
        $_SESSION["franchise_id"],
        $_POST["date_vente"],
        $_POST["nom"],
        $_POST["montant"]
    );
    header("Location: ventes.php");
    exit;
}

// Historique des ventes
$ventes = Vente::getByFranchise($pdo, $_SESSION["franchise_id"]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes ventes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar.php"; ?>

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- AJOUT VENTE -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"> Ajouter une vente</h4>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Date de la vente</label>
                            <input type="date" name="date_vente" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom de la vente</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Montant (€)</label>
                            <input type="number" step="0.01" name="montant" class="form-control" required>
                        </div>

                        <button class="btn btn-success">Ajouter</button>
                    </form>
                </div>
            </div>

            <!-- HISTORIQUE -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"> Historique des ventes</h4>
                </div>

                <div class="card-body">
                    <?php if (empty($ventes)): ?>
                        <p class="text-muted">Aucune vente enregistrée.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Nom</th>
                                        <th>Montant (€)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventes as $v): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($v["date_vente"]) ?></td>
                                            <td><?= htmlspecialchars($v["nom"]) ?></td>
                                            <td><?= number_format($v["montant"], 2, ',', ' ') ?> €</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <a href="../../pdf/ventes_pdf.php" target="_blank" class="btn btn-outline-primary">
                         Générer PDF
                    </a>

                    <a href="dashboard.php" class="btn btn-secondary ms-2">
                        ⬅ Retour dashboard
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
