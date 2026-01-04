<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Produit.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;
$message = "";

// GESTION FORMULAIRES (FIX)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 1. MODIFICATION d'abord (priorité)
    if ($action === "edit" && $id) {
        Produit::updateByAdmin(
            $pdo,
            $_POST["nom"],
            $_POST["prix"],
            $_POST["stock"],
            $_POST["entrepot_id"],
            $id
        );
        header("Location: produits.php?success=edit");
        exit;
    }
    
    // 2. AJOUT seulement si PAS édition
    Produit::create(
        $pdo,
        $_POST["nom"],
        $_POST["prix"],
        $_POST["stock"],
        $_POST["entrepot_id"]
    );
    header("Location: produits.php?success=add");
    exit;
}

// SUPPRESSION
if ($action === "delete" && $id) {
    Produit::delete($pdo, $id);
    header("Location: produits.php?success=delete");
    exit;
}

$produits = Produit::getAll($pdo);
$entrepots = Produit::getEntrepots($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../../includes/navbar_admin.php"; ?>
<div class="container-xl mt-5">
    <h1 class="mb-4">Gestion des produits</h1>
    <?php if ($action === "list" || $action === "add"): ?>
    <div class="card shadow mb-5">
        <div class="card-body">
            <h4 class="mb-3">Ajouter un produit</h4>

            <form method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nom du produit</label>
                    <input name="nom" class="form-control" placeholder="Nom du produit" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Prix unitaire (€)</label>
                    <input type="number" name="prix" step="0.01" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" min="0" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Entrepôt</label>
                    <select name="entrepot_id" class="form-select" required>
                        <?php foreach ($entrepots as $e): ?>
                            <option value="<?= $e["id"] ?>">
                                <?= htmlspecialchars($e["nom"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-success">
                        ➕ Créer le produit
                    </button>
                </div>

            </form>
        </div>
    </div>
    <?php endif; ?>
    <div class="card shadow mb-5">
        <div class="card-body">
            <h4 class="mb-3">Stocks disponibles</h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Produit</th>
                            <th>Prix unitaire (€)</th>
                            <th>Stock</th>
                            <th>Entrepôt</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p["nom"]) ?></td>
                            <td><?= number_format($p["prix"], 2, ',', ' ') ?> €</td>
                            <td>
                                <span class="badge <?= $p["stock"] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $p["stock"] ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($p["entrepot"]) ?></td>
                            <td class="text-end">
                                <a href="?action=edit&id=<?= $p["id"] ?>" class="btn btn-sm btn-outline-primary">
                                    ✏️
                                </a>
                                <a href="?action=delete&id=<?= $p["id"] ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Supprimer ce produit ?')">
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

    <?php if ($action === "edit" && $id): 
    $produits = Produit::getById($pdo, $id);
    ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <h4 class="mb-3">Modifier le produit</h4>

            <form method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input name="nom" value="<?= htmlspecialchars($produits["nom"]) ?>" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Prix (€)</label>
                    <input name="prix" value="<?= htmlspecialchars($produits["prix"]) ?>" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Stock</label>
                    <input name="stock" value="<?= htmlspecialchars($produits["stock"]) ?>" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Entrepôt</label>
                    <select name="entrepot_id" class="form-select" required>
                        <?php foreach ($entrepots as $e): ?>
                            <option value="<?= $e["id"] ?>">
                                <?= htmlspecialchars($e["nom"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary">Enregistrer</button>
                    <a href="produits.php" class="btn btn-outline-secondary">Annuler</a>
                </div>

            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-outline-dark">
            Retour admin
        </a>
    </div>
</div>
</body>
</html>
