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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - Camions</title>
</head>
<body>
<?php include "../../includes/navbar_admin.php"; ?>
<h1>Gestion du parc de camions</h1>

<?php if ($action === "list"): 
$camions = Camion::getAll($pdo);
?>
<a href="?action=add">Ajouter un camion</a><br><br>

<table border="1" cellpadding="5">
<tr>
    <th>Immatriculation</th>
    <th>Modèle</th>
    <th>Statut</th>
    <th>Type de panne</th>
    <th>Panne</th>
    <th>Franchisé</th>
    <th>Actions</th>
</tr>

<?php foreach ($camions as $c): ?>
<tr>
    <td><?= htmlspecialchars($c["immatriculation"]) ?></td>
    <td><?= htmlspecialchars($c["modele"]) ?></td>
    <td><?= htmlspecialchars($c["statut"]) ?></td>
    <td><?= htmlspecialchars($c["type_panne"] ?? "Aucune") ?></td>
    <td><?= htmlspecialchars($c["panne_description"] ?? "Aucune") ?></td>
    <td><?= $c["franchise_nom"] ?? "Non attribué" ?></td>
    <td>
        <a href="?action=edit&id=<?= $c["id"] ?>">✏️</a>
        <a href="?action=delete&id=<?= $c["id"] ?>"
           onclick="return confirm('Supprimer ce camion ?')">🗑️</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<a href="dashboard.php">Dashboard Admin</a>
<?php endif; ?>

<?php if ($action === "add"): 
$franchises = Franchise::getAll($pdo);
?>

<h2>Ajouter un camion</h2>

<form method="POST" action="?action=add">
    <label>Immatriculation</label><br>
    <input name="immatriculation" placeholder="Immatriculation" required><br><br>
    <label>Modèle</label><br>
    <select name="modele" required>
        <?php foreach (Camion::getModeles() as $modele): ?>
            <option value="<?= $modele ?>"><?= $modele ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <label>Statut</label><br>
    <select name="statut">
        <option value="actif">Disponible</option>
        <option value="panne">En panne</option>
        <option value="maintenance">Maintenance</option>
    </select><br><br>

    <label>Attribuer à un franchisé</label><br>
    <select name="franchise_id">
        <option value="">-- Non attribué --</option>
        <?php foreach ($franchises as $f): ?>
            <option value="<?= $f["id"] ?>"><?= $f["nom"] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button>Créer</button>
</form>

<a href="camions.php">Retour</a>

<?php endif; ?>

<!-- j'appelle la modification -->
<?php if ($action === "edit" && $id): 
$camion = Camion::getById($pdo, $id);
$franchises = Franchise::getAll($pdo);
?>

<h2>Modifier le camion</h2>

<form method="POST" action="?action=edit&id=<?= $id ?>">
    <label>Immatriculation</label><br>
    <input name="immatriculation" placeholder="Immatriculation" required><br><br>
    <label>Modèle</label><br>
    <select name="modele" required>
        <?php foreach (Camion::getModeles() as $modele): ?>
            <option value="<?= $modele ?>"><?= $modele ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <label>Statut</label><br>
    <select name="statut">
        <option value="actif" <?= $camion["statut"]=="actif"?"selected":"" ?>>Disponible</option>
        <option value="panne" <?= $camion["statut"]=="panne"?"selected":"" ?>>En panne</option>
        <option value="maintenance" <?= $camion["statut"]=="maintenance"?"selected":"" ?>>Maintenance</option>
    </select><br><br>
    <label>Attribuer à un franchisé</label><br>
    <select name="franchise_id">
        <option value="">-- Non attribué --</option>
        <?php foreach ($franchises as $f): ?>
            <option value="<?= $f["id"] ?>" <?= $camion["franchise_id"]==$f["id"]?"selected":"" ?>>
                <?= $f["nom"] ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button>Enregistrer</button>
</form>

<a href="camions.php">⬅ Retour</a>

<?php endif; ?>

</body>
</html>
