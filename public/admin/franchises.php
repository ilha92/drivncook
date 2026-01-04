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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - Franchisés</title>
</head>
<body>
<?php include "../../includes/navbar_admin.php"; ?>
<h1>Gestion des franchisés</h1>

<?php if ($action === "list"): 
$franchises = Franchise::getAll($pdo);
?>

<a href="?action=add">Nouveau franchisé</a><br><br>

<table border="1" cellpadding="5">
<tr>
    <th>Nom</th>
    <th>Email</th>
    <th>Droit d'entrée</th>
    <th>Ville</th>
    <th>Actions</th>
</tr>

<?php foreach ($franchises as $f): ?>
<tr>
    <td><?= htmlspecialchars($f["nom"]) ?></td>
    <td><?= htmlspecialchars($f["email"]) ?></td>
    <td><?= htmlspecialchars($f["droit_entree"] === 'accepte' ? 'Payé' : 'Non payé') ?></td>
    <td><?= htmlspecialchars($f["ville"]) ?></td>
    <td>
        <a href="?action=detail&id=<?= $f["id"] ?>">🔍</a>
        <a href="?action=edit&id=<?= $f["id"] ?>">✏️</a>
        <a href="?action=delete&id=<?= $f["id"] ?>"
           onclick="return confirm('Supprimer ce franchisé ?')">🗑️</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<br><br> 
<a href="../../pdf/franchises_pdf.php" target="_blank">Générer PDF des franchises</a>
<br><br>
<a href="dashboard.php">Dashboard</a>
<?php endif; ?>
<?php if ($action === "add"): ?>

<h2>Ajouter un franchisé</h2>

<form method="POST">
    <input name="nom" placeholder="Nom" required><br><br>
    <input name="email" placeholder="Email" required><br><br>
    <input name="password" type="password" placeholder="Mot de passe" required><br><br>
    <input name="ville" placeholder="Ville"><br><br>
    <input name="telephone" placeholder="Téléphone"><br><br>
    <label>Date d'entrée :</label><br>
    <input type="date" name="date_entree" required><br><br>
    <button>Créer</button>
</form>

<a href="franchises.php">Retour</a>

<?php endif; ?>
<?php if ($action === "edit" && $id): 
$franchise = Franchise::getById($pdo, $id);
?>

<h2>Modifier le franchisé</h2>

<form method="POST">
    <input name="nom" value="<?= htmlspecialchars($franchise["nom"]) ?>"><br><br>
    <input name="email" value="<?= htmlspecialchars($franchise["email"]) ?>"><br><br>
    <label>Droit d'entrée :</label><br>
    <select name="droit_entree">
        <option value="refuse" <?= $franchise["droit_entree"] === 'refuse' ? 'selected' : '' ?>>Non payé</option>
        <option value="accepte" <?= $franchise["droit_entree"] === 'accepte' ? 'selected' : '' ?>>Payé</option>
    </select><br><br>
    <input name="ville" value="<?= htmlspecialchars($franchise["ville"]) ?>"><br><br>
    <input name="telephone" value="<?= htmlspecialchars($franchise["telephone"]) ?>"><br><br>
    <button>Enregistrer</button>
</form>

<a href="franchises.php">Retour</a>

<?php endif; ?>

<!-- Détail / Historique -->
<?php if ($action === "detail" && $id): 
$franchise = Franchise::getById($pdo, $id);
$ventes = Franchise::getVentes($pdo, $id);

$totalCA = 0;
foreach ($ventes as $v) {
    $totalCA += $v["montant"];
}
$redevance = $totalCA * 0.04;
?>

<h2>Détail du franchisé</h2>

<p><b>Nom :</b> <?= htmlspecialchars($franchise["nom"]) ?></p>
<p><b>Email :</b> <?= htmlspecialchars($franchise["email"]) ?></p>
<p><b>Date d'entrée :</b> <?= $franchise["date_entree"] ?></p>
<p><b>Droit d'entrée :</b> <?= $franchise["droit_entree"] === 'accepte' ? 'Payé' : 'Non payé' ?></p>

<h3>Historique des ventes</h3>

<?php if (count($ventes) > 0): ?>
<ul>
<?php foreach ($ventes as $v): ?>
    <li><?= $v["date_vente"] ?> - <?= $v["montant"] ?> €</li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>Aucune vente enregistrée.</p>
<?php endif; ?>

<p><b>Chiffre d'affaires total :</b> <?= $totalCA ?> €</p>
<p><b>4 % à reverser :</b> <?= $redevance ?> €</p>
<a href="franchises.php">⬅ Retour</a>

<?php endif; ?>

</body>
</html>
