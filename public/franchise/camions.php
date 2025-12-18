<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Camion.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
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
<html>
<head><meta charset="UTF-8"><title>Mes camions</title></head>
<body>

<h1>Mes camions</h1>

<table border="1">
<tr>
    <th>Immatriculation</th>
    <th>Modèle</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>

<?php foreach ($camions as $c): ?>
<tr>
    <td><?= $c["immatriculation"] ?></td>
    <td><?= $c["modele"] ?></td>
    <td><?= $c["statut"] ?></td>
    <td>
        <a href="?action=panne&id=<?= $c['id'] ?>">🚨 Déclarer panne</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php if ($action === "panne" && $id): ?>
<h2>Déclarer une panne</h2>
<form method="POST">
    <input type="hidden" name="camion_id" value="<?= $id ?>">
    <input type="date" name="date_panne" required><br><br>
    <input name="type_panne" placeholder="Type de panne" required><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>
    <button>Envoyer</button>
</form>
<?php endif; ?>

</body>
</html>
