<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Approvisionnement.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../../access/login.php");
    exit;
}

$action = $_GET["action"] ?? null;

// Suppression d'une commande
if ($action === "delete" && isset($_GET["id"])) {
    Approvisionnement::delete($pdo, $_GET["id"]);
    header("Location: approvisionnements.php");
    exit;
}

// Validation d'une commande d'un franchisé
if ($action === "valider") {

    Approvisionnement::validerCommande(
        $pdo,
        $_GET["commande_id"],
        $_GET["produit_id"],
        $_GET["quantite"]
    );

    header("Location: approvisionnements.php");
    exit;
}

// Liste des commandes
$commandes = Approvisionnement::getAllCommandes($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Approvisionnements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../../includes/navbar_admin.php"; ?>
<div class="container-xl mt-5">
    <h1 class="mb-4">Gestion des commandes</h1>

    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Franchisé</th>
                            <th>Produit</th>
                            <th>Stock actuel</th>
                            <th>Quantité demandée</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($commandes as $c): ?>
                        <tr>
                            <td><?= $c["date_commande"] ?></td>
                            <td><?= htmlspecialchars($c["franchise"]) ?></td>
                            <td><?= htmlspecialchars($c["produit"]) ?></td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= $c["stock"] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= $c["quantite"] ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($c["statut"] === "en attente"): ?>
                                    <span class="badge bg-warning text-dark">
                                        En attente
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        Validée
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">

                                <?php if ($c["statut"] === "en attente"): ?>

                                    <a href="approvisionnements.php?action=valider&commande_id=<?= $c['id'] ?>&produit_id=<?= $c['produit_id'] ?>&quantite=<?= $c['quantite'] ?>"
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Valider cette commande ?')">
                                        Valider
                                    </a>

                                <?php endif; ?>

                                <a href="approvisionnements.php?action=delete&id=<?= $c['id'] ?>"
                                   class="btn btn-sm btn-outline-danger ms-1"
                                   onclick="return confirm('Supprimer cette commande ?')">
                                    🗑️
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-dark">
            Retour admin
        </a>
    </div>

</div>

</body>
</html>
