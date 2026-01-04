<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Camion.php";
require_once "../../src/models/Franchise.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;

//ajout d'un camion
if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
    Camion::create(
        $pdo,
        $_POST["immatriculation"],
        $_POST["modele"],
        $_POST["statut"],
        $_POST["franchise_id"] ?: null
    );
    header("Location: camions.php");
    exit;
}

//modification d'un camion
if ($action === "edit" && $id && $_SERVER["REQUEST_METHOD"] === "POST") {
    Camion::update(
        $pdo,
        $_POST["immatriculation"],
        $_POST["modele"],
        $_POST["statut"],
        $_POST["franchise_id"] ?: null,
        $id
    );
    header("Location: camions.php");
    exit;
}

//suppression d'un camion
if ($action === "delete" && $id) {
    Camion::delete($pdo, $id);
    header("Location: camions.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Camions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar_admin.php"; ?>

<div class="container-xl mt-5">

<h1 class="mb-4">Gestion du parc de camions</h1>

<?php if ($action === "list"): 
$camions = Camion::getAll($pdo);
?>

<div class="d-flex justify-content-end mb-3">
    <a href="?action=add" class="btn btn-success">
        Ajouter un camion
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Immatriculation</th>
                    <th>Modèle</th>
                    <th>Statut</th>
                    <th>Type de panne</th>
                    <th>Panne</th>
                    <th>Franchisé</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($camions as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c["immatriculation"]) ?></td>
                <td><?= htmlspecialchars($c["modele"]) ?></td>
                <td>
                    <span class="badge 
                        <?= $c["statut"] === "actif" ? "bg-success" : "" ?>
                        <?= $c["statut"] === "panne" ? "bg-danger" : "" ?>
                        <?= $c["statut"] === "maintenance" ? "bg-warning text-dark" : "" ?>">
                        <?= htmlspecialchars($c["statut"]) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($c["type_panne"] ?? "Aucune") ?></td>
                <td><?= htmlspecialchars($c["panne_description"] ?? "Aucune") ?></td>
                <td><?= $c["franchise_nom"] ?? "Non attribué" ?></td>
                <td class="text-end">
                    <a href="?action=edit&id=<?= $c["id"] ?>" class="btn btn-sm btn-outline-primary">
                        ✏️
                    </a>
                    <a href="?action=delete&id=<?= $c["id"] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Supprimer ce camion ?')">
                        🗑️
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>
<div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-outline-dark">
        Dashboard Admin
    </a>
</div>
<?php endif; ?>
<?php if ($action === "add"): 
$franchises = Franchise::getAll($pdo);
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-body">

                <h2 class="mb-4">Ajouter un camion</h2>

                <form method="POST" action="?action=add">

                    <div class="mb-3">
                        <label class="form-label">Immatriculation</label>
                        <input name="immatriculation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Modèle</label>
                        <select name="modele" class="form-select" required>
                            <?php foreach (Camion::getModeles() as $modele): ?>
                                <option value="<?= $modele ?>"><?= $modele ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="actif">Disponible</option>
                            <option value="panne">En panne</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Attribuer à un franchisé</label>
                        <select name="franchise_id" class="form-select">
                            <option value="">-- Non attribué --</option>
                            <?php foreach ($franchises as $f): ?>
                                <option value="<?= $f["id"] ?>"><?= $f["nom"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-success btn-lg">
                            Créer
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <div class="text-center mt-3">
            <a href="camions.php" class="btn btn-outline-dark">
                ⬅ Retour
            </a>
        </div>

    </div>
</div>
<?php endif; ?>
<?php if ($action === "edit" && $id): 
$camion = Camion::getById($pdo, $id);
$franchises = Franchise::getAll($pdo);
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-body">

                <h2 class="mb-4">Modifier le camion</h2>

                <form method="POST" action="?action=edit&id=<?= $id ?>">

                    <div class="mb-3">
                        <label class="form-label">Immatriculation</label>
                        <input name="immatriculation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Modèle</label>
                        <select name="modele" class="form-select" required>
                            <?php foreach (Camion::getModeles() as $modele): ?>
                                <option value="<?= $modele ?>"><?= $modele ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="actif" <?= $camion["statut"]=="actif"?"selected":"" ?>>Disponible</option>
                            <option value="panne" <?= $camion["statut"]=="panne"?"selected":"" ?>>En panne</option>
                            <option value="maintenance" <?= $camion["statut"]=="maintenance"?"selected":"" ?>>Maintenance</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Attribuer à un franchisé</label>
                        <select name="franchise_id" class="form-select">
                            <option value="">-- Non attribué --</option>
                            <?php foreach ($franchises as $f): ?>
                                <option value="<?= $f["id"] ?>" <?= $camion["franchise_id"]==$f["id"]?"selected":"" ?>>
                                    <?= $f["nom"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg">
                            Enregistrer
                        </button>
                    </div>

                </form>

            </div>
        </div>
        <div class="text-center mt-3">
            <a href="camions.php" class="btn btn-outline-dark">
                Retour
            </a>
        </div>

    </div>
</div>

<?php endif; ?>

</div>
</body>
</html>

