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

if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
    Camion::create($pdo, $_POST["immatriculation"], $_POST["modele"], $_POST["franchise_id"] ?: null);
    header("Location: camions.php");
    exit;
}

if ($action === "delete" && $id) {
    Camion::delete($pdo, $id);
    header("Location: camions.php");
    exit;
}

if ($action === "reparer" && $id) {
    Camion::reparerCamion($pdo, $id);
    header("Location: camions.php");
    exit;
}

$camions = Camion::getAll($pdo);
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Admin - Camions</title></head>
<body>

<h1>Gestion du parc de camions</h1>
<a href="?action=add">➕ Ajouter un camion</a><br><br>

<table border="1">
<tr>
    <th>Immatriculation</th>
    <th>Modèle</th>
    <th>Statut</th>
    <th>Franchisé</th>
    <th>Actions</th>
</tr>
<?php foreach ($camions as $c): ?>
<tr>
    <td><?= $c["immatriculation"] ?></td>
    <td><?= $c["modele"] ?></td>
    <td><?= $c["statut"] ?></td>
    <td><?= $c["nom"] ?? "Libre" ?></td>
    <td>
        <a href="?action=reparer&id=<?= $c["id"] ?>" onclick="return confirm('Réparer ?')">✅ Réparer</a>
        <a href="?action=delete&id=<?= $c["id"] ?>" onclick="return confirm('Supprimer ?')">🗑️</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php if ($action === "add"):
$franchises = Franchise::getAll($pdo);
?>
<h2>Nouveau camion</h2>
<form method="POST">
    <input name="immatriculation" required placeholder="Immatriculation"><br><br>
    <input name="modele" required placeholder="Modèle"><br><br>
    <select name="franchise_id">
        <option value="">-- Libre --</option>
        <?php foreach ($franchises as $f): ?>
            <option value="<?= $f['id'] ?>"><?= $f['nom'] ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <button>Créer</button>
</form>
<?php endif; ?>
<a href="dashboard.php">⬅ Retour</a>
</body>
</html>
