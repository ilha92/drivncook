<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Camion.php";

// Sécurité : uniquement franchisé
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

// Vérification droit d'entrée du franchisé
if ($_SESSION["droit_entree"] !== "accepte") {
    header("Location: droit_entree.php");
    exit;
}


$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;

$camions = Camion::getByFranchise($pdo, $_SESSION["franchise_id"]);

if ($action === "panne" && $_SERVER["REQUEST_METHOD"] === "POST") {
    Camion::addPanne(
        $pdo,
        $_POST["camion_id"],
        $_POST["date_panne"],
        $_POST["type_panne"],
        $_POST["description"]
    );
    header("Location: camions.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes camions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar.php"; ?>

<div class="container mt-5">

    <h1 class="mb-4 text-center">Mes camions</h1>

    <!-- TABLE CAMIONS -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Immatriculation</th>
                    <th>Modèle</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($camions as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c["immatriculation"]) ?></td>
                    <td><?= htmlspecialchars($c["modele"]) ?></td>
                    <td>
                        <?php if ($c["statut"] === "actif"): ?>
                            <span class="badge bg-success">Actif</span>
                        <?php elseif ($c["statut"] === "panne"): ?>
                            <span class="badge bg-danger">En panne</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Maintenance</span>
                        <?php endif; ?>
                    </td>
                    <td class="d-flex justify-content-center gap-2">
                        <a href="?action=panne&id=<?= $c['id'] ?>" class="btn btn-outline-danger btn-sm">
                             Panne
                        </a>
                        <a href="?action=historique&id=<?= $c['id'] ?>" class="btn btn-outline-primary btn-sm">
                             Historique
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- DECLARATION PANNE -->
    <?php if ($action === "panne" && $id): ?>
        <div class="card shadow mt-5">
            <div class="card-body">
                <h3 class="mb-4">Déclarer une panne</h3>
                <form method="POST">
                    <input type="hidden" name="camion_id" value="<?= $id ?>">

                    <div class="mb-3">
                        <label class="form-label">Date de la panne</label>
                        <input type="date" name="date_panne" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type de panne</label>
                        <input name="type_panne" class="form-control" placeholder="Ex : moteur, frein…" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <button class="btn btn-danger">Envoyer</button>
                    <a href="camions.php" class="btn btn-secondary ms-2">Annuler</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- HISTORIQUE DES PANNES -->
    <?php if ($action === "historique" && $id): 
        $pannes = Camion::getPannes($pdo, $id);
    ?>
        <div class="card shadow mt-5">
            <div class="card-body">
                <h3 class="mb-4"> Historique des pannes</h3>

                <?php if (count($pannes) === 0): ?>
                    <div class="alert alert-info">Aucune panne enregistrée.</div>
                <?php else: ?>
                    <table class="table table-striped text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pannes as $p): ?>
                            <tr>
                                <td><?= $p["date_panne"] ?></td>
                                <td><?= htmlspecialchars($p["description"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <a href="camions.php" class="btn btn-secondary"> Retour</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center mt-5">
        <a href="dashboard.php" class="btn btn-outline-dark">
             Dashboard Franchise
        </a>
    </div>

</div>

</body>
</html>