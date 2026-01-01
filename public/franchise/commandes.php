<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Commande.php";

// Sécurité : uniquement franchisé
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

// Vérification droit d'entrée == accepte
if ($_SESSION["droit_entree"] !== "accepte") {
    header("Location: droit_entree.php");
    exit;
}

$action = $_GET["action"] ?? "list";

// Gestion des commandes
$message = "";

if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $result = Commande::create(
        $pdo,
        $_SESSION["franchise_id"],
        $_POST["produit_id"],
        $_POST["quantite"]
    );

    if ($result === true) {
        header("Location: commandes.php?success=1");
        exit;
    } else {
        $message = $result; // ex: "Stock insuffisant"
    }
}

$produits = Commande::getProduits($pdo);
$commandes = Commande::getByFranchise($pdo, $_SESSION["franchise_id"]);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mes commandes</title>
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm mx-auto" style="max-width: 900px;">
        <div class="card-body">

            <h2 class="text-center mb-4">Passer une commande</h2>

            <!-- FORMULAIRE -->
            <form method="POST" action="?action=add">

                <div class="mb-3">
                    <label class="form-label">Produit</label>
                    <select name="produit_id" class="form-select" required>
                        <?php foreach ($produits as $p): ?>
                            <option value="<?= $p["id"] ?>">
                                <?= $p["nom"] ?> (<?= $p["prix"] ?> € | Stock : <?= $p["stock"] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantité</label>
                    <input type="number" name="quantite" class="form-control" min="1" required>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div class="d-grid">
                    <button class="btn btn-primary btn-lg">Commander</button>
                </div>
            </form>

        </div>
    </div>

    <!-- HISTORIQUE -->
    <div class="card shadow-sm mt-5">
        <div class="card-body">

            <h3 class="mb-4 text-center">Historique de mes commandes</h3>

            <?php if (count($commandes) === 0): ?>
                <p class="text-center text-muted">Aucune commande.</p>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Prix total</th>
                                <th>Quantité</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($commandes as $c): ?>
                            <tr>
                                <td><?= $c["date_commande"] ?></td>
                                <td><?= $c["produit"] ?></td>
                                <td><?= number_format($c["prix"] * $c["quantite"], 2, ',', ' ') ?> €</td>
                                <td><?= $c["quantite"] ?></td>
                                <td>
                                    <span class="badge bg-success">
                                        <?= ucfirst($c["statut"]) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-secondary">← Retour dashboard</a>
            </div>

        </div>
    </div>

</div>
</body>
</html>
