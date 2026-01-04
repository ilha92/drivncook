<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Entrepot.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;
$message = "";
$entrepot = null;

// Traitement des formulaires ajout et modification
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 1. on modifie un entrepôt
    if ($action === "edit" && $id) {
        $actif = isset($_POST["actif"]) ? 1 : 0;
        if (Entrepot::update($pdo, $id, $_POST["nom"], $_POST["ville"], $_POST["prix"], $actif)) {
            $message = "Entrepôt modifié !";
        } else {
            $message = "Erreur modification.";
        }
        header("Location: entrepots.php");
        exit;
    }
    
    // 2. j'ajoute un entrepôt
    if (Entrepot::create($pdo, $_POST["nom"], $_POST["ville"], $_POST["prix"])) {
        $message = "Entrepôt ajouté !";
    } else {
        $message = "Erreur ajout.";
    }
}

// je supprime un entrepôt
if ($action === "delete" && $id) {
    if (Entrepot::delete($pdo, $id)) {
        $message = "Entrepôt supprimé !";
    }
    header("Location: entrepots.php");
    exit;
}

// DONNÉES
$entrepots = Entrepot::getAll($pdo);
if ($action === "edit" && $id) {
    $entrepot = Entrepot::getById($pdo, $id);
    if (!$entrepot) {
        header("Location: entrepots.php?error=id-inexistant");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des entrepôts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar_admin.php"; ?>

<div class="container mt-5">
    <h1 class="mb-4">Gestion des entrepôts</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- on peut ajouter un entrepôt -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5>Ajouter un entrepôt</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input class="form-control" name="nom" placeholder="Nom" required>
                </div>
                <div class="col-md-4">
                    <input class="form-control" name="ville" placeholder="Ville" required>
                </div>
                <div class="col-md-4">
                    <input class="form-control" name="prix" type="number" step="0.01" placeholder="Prix (€)" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- je crée le formulaire d'édition -->
    <?php if ($action === "edit" && $entrepot): ?>
        <div class="card mb-4 shadow">
            <div class="card-body">
                <h5>Modifier l'entrepôt #<?= $entrepot['nom'] ?></h5>
                <form method="POST" action="?action=edit&id=<?= $id ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Nom</label>
                            <input class="form-control" name="nom" value="<?= htmlspecialchars($entrepot["nom"]) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ville</label>
                            <input class="form-control" name="ville" value="<?= htmlspecialchars($entrepot["ville"]) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Prix (€)</label>
                            <input class="form-control" name="prix" type="number" step="0.01" 
                                   value="<?= htmlspecialchars($entrepot["prix"]) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actif" id="actif" 
                                       <?= $entrepot["actif"] ? "checked" : "" ?> value="1">
                                <label class="form-check-label" for="actif">Actif</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success">Enregistrer</button>
                            <a href="entrepots.php" class="btn btn-secondary">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- on n'a notre tableau liste ou nos entrepôts créer sont affichés -->
    <div class="card shadow">
        <div class="card-body">
            <h5>Liste des entrepôts (<?= count($entrepots) ?>)</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Prix (€)</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entrepots as $e): ?>
                        <tr>
                            <td><?= $e["id"] ?></td>
                            <td><?= htmlspecialchars($e["nom"]) ?></td>
                            <td><?= htmlspecialchars($e["ville"]) ?></td>
                            <td><?= number_format($e["prix"], 2, ',', ' ') ?> €</td>
                            <td>
                                <span class="badge <?= $e["actif"] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $e["actif"] ? 'Actif' : 'Désactivé' ?>
                                </span>
                            </td>
                            <td>
                                <a href="?action=edit&id=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="?action=delete&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Supprimer <?= htmlspecialchars($e['nom']) ?> ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="dashboard.php">Retour au dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
