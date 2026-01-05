<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Franchise.php";

// Sécurité admin
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Action par défaut
$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;

// J'ajoute d'un franchisé
if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
    Franchise::create(
        $pdo,
        $_POST["nom"],
        $_POST["email"],
        $_POST["password"],
        $_POST["ville"],
        $_POST["telephone"],
        $_POST["date_entree"]
    );
    header("Location: franchises.php");
    exit;
}

// Modification d'un franchisé
if ($action === "edit" && $id && $_SERVER["REQUEST_METHOD"] === "POST") {
    Franchise::updateByAdmin(
        $pdo,
        $_POST["nom"],
        $_POST["email"],
        $_POST["droit_entree"],
        $_POST["ville"],
        $_POST["telephone"],
        $id
    );
    header("Location: franchises.php");
    exit;
}
// suppression d'un franchisé
if ($action === "delete" && $id) {
    Franchise::delete($pdo, $id);
    header("Location: franchises.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Franchisés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar_admin.php"; ?>

<div class="container-xl mt-5">

    <h1 class="mb-4">Gestion des franchisés</h1>

    <?php if ($action === "list"): 
    $franchises = Franchise::getAll($pdo);
    ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-muted mb-0">Liste des franchisés</h5>
        <a href="?action=add" class="btn btn-success">
            Nouveau franchisé
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Droit d'entrée</th>
                        <th>Ville</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($franchises as $f): ?>
                    <tr>
                        <td><?= htmlspecialchars($f["nom"]) ?></td>
                        <td><?= htmlspecialchars($f["email"]) ?></td>
                        <td>
                            <?php if ($f["droit_entree"] === 'accepte'): ?>
                                <span class="badge bg-success">Payé</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Non payé</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($f["ville"]) ?></td>
                        <td class="text-end">
                            <a href="?action=detail&id=<?= $f["id"] ?>" class="btn btn-sm btn-outline-primary">🔍</a>
                            <a href="?action=edit&id=<?= $f["id"] ?>" class="btn btn-sm btn-outline-warning">✏️</a>
                            <a href="?action=delete&id=<?= $f["id"] ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Supprimer ce franchisé ?')">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3">
        <a href="../../pdf/franchises_pdf.php" target="_blank" class="btn btn-outline-secondary">
            Générer PDF
        </a>
        <a href="dashboard.php" class="btn btn-outline-dark">
            Dashboard
        </a>
    </div>

    <?php endif; ?>
    <?php if ($action === "add"): ?>

    <div class="card shadow mt-4">
        <div class="card-body">
            <h3 class="mb-4">Ajouter un franchisé</h3>

            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input name="nom" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mot de passe</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ville</label>
                    <input name="ville" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input name="telephone" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d'entrée</label>
                    <input type="date" name="date_entree" class="form-control" required>
                </div>

                <div class="col-12">
                    <button class="btn btn-success">Créer</button>
                    <a href="franchises.php" class="btn btn-secondary ms-2">Retour</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($action === "edit" && $id): 
    $franchise = Franchise::getById($pdo, $id);
    ?>
    <div class="card shadow mt-4">
        <div class="card-body">
            <h3 class="mb-4">Modifier le franchisé</h3>

            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input name="nom" value="<?= htmlspecialchars($franchise["nom"]) ?>" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input name="email" value="<?= htmlspecialchars($franchise["email"]) ?>" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Droit d'entrée</label>
                    <select name="droit_entree" class="form-select">
                        <option value="refuse" <?= $franchise["droit_entree"] === 'refuse' ? 'selected' : '' ?>>Non payé</option>
                        <option value="accepte" <?= $franchise["droit_entree"] === 'accepte' ? 'selected' : '' ?>>Payé</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ville</label>
                    <input name="ville" value="<?= htmlspecialchars($franchise["ville"]) ?>" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input name="telephone" value="<?= htmlspecialchars($franchise["telephone"]) ?>" class="form-control">
                </div>

                <div class="col-12">
                    <button class="btn btn-warning">Enregistrer</button>
                    <a href="franchises.php" class="btn btn-secondary ms-2">Retour</a>
                </div>
            </form>
        </div>
    </div>

    <?php endif; ?>

    <?php if ($action === "detail" && $id): 
    $franchise = Franchise::getById($pdo, $id);
    $ventes = Franchise::getVentes($pdo, $id);

    $totalCA = 0;
    foreach ($ventes as $v) {
        $totalCA += $v["montant"];
    }
    $redevance = $totalCA * 0.04;
    ?>

    <div class="card shadow mt-4">
        <div class="card-body">
            <h3>Détail du franchisé</h3>

            <p><strong>Nom :</strong> <?= htmlspecialchars($franchise["nom"]) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($franchise["email"]) ?></p>
            <p><strong>Date d'entrée :</strong> <?= $franchise["date_entree"] ?></p>
            <p><strong>Droit d'entrée :</strong> <?= $franchise["droit_entree"] === 'accepte' ? 'Payé' : 'Non payé' ?></p>

            <hr>

            <h5>Historique des ventes</h5>

            <?php if (count($ventes) > 0): ?>
                <ul class="list-group mb-3">
                    <?php foreach ($ventes as $v): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?= $v["date_vente"] ?></span>
                            <strong><?= $v["montant"] ?> €</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune vente enregistrée.</p>
            <?php endif; ?>

            <p><strong>CA total :</strong> <?= $totalCA ?> €</p>
            <p><strong>4 % à reverser :</strong> <?= $redevance ?> €</p>

            <a href="franchises.php" class="btn btn-outline-dark">Retour</a>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
