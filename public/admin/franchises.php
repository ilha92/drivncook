<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Franchise.php";

/* =========================
   SÉCURITÉ ADMIN
   ========================= */
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

/* =========================
   ROUTAGE SIMPLE
   ========================= */
$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;

/* =========================
   SUPPRESSION
   ========================= */
if ($action === "delete" && $id) {
    Franchise::delete($pdo, $id);
    header("Location: franchises.php");
    exit;
}

/* =========================
   AJOUT
   ========================= */
if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
    Franchise::create(
        $pdo,
        $_POST["nom"],
        $_POST["email"],
        $_POST["ville"],
        $_POST["telephone"],
        $_POST["date_entree"]
    );
    header("Location: franchises.php");
    exit;
}

/* =========================
   MODIFICATION
   ========================= */
if ($action === "edit" && $id && $_SERVER["REQUEST_METHOD"] === "POST") {
    Franchise::update(
        $pdo,
        $_POST["nom"],
        $_POST["email"],
        $_POST["ville"],
        $_POST["telephone"],
        $id
    );
    header("Location: franchises.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des franchisés</title>
</head>
<body>

<?php
/* =========================
   LISTE DES FRANCHISÉS
   ========================= */
if ($action === "list"):
    $franchises = Franchise::getAll($pdo);
?>

<h1>Gestion des franchisés</h1>

<a href="?action=add">➕ Nouveau franchisé</a><br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Ville</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($franchises as $f): ?>
        <tr>
            <td><?= $f["nom"] ?></td>
            <td><?= $f["email"] ?></td>
            <td><?= $f["ville"] ?></td>
            <td>
                <a href="?action=detail&id=<?= $f['id'] ?>">🔍</a>
                <a href="?action=edit&id=<?= $f['id'] ?>">✏️</a>
                <a href="?action=delete&id=<?= $f['id'] ?>"
                   onclick="return confirm('Supprimer ce franchisé ?')">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
<a href="dashboard.php">Retour Dashboard</a>
<?php
/* =========================
   AJOUT
   ========================= */
elseif ($action === "add"):
?>

<h2>Nouveau franchisé</h2>

<form method="POST">
    <input name="nom" placeholder="Nom" required><br><br>
    <input name="email" placeholder="Email" required><br><br>
    <input name="ville" placeholder="Ville"><br><br>
    <input name="telephone" placeholder="Téléphone"><br><br>
    <input type="date" name="date_entree" required><br><br>
    <button>Créer</button>
</form>

<a href="franchises.php">⬅ Retour</a>

<?php
/* =========================
   MODIFICATION
   ========================= */
elseif ($action === "edit" && $id):
    $franchise = Franchise::getById($pdo, $id);
?>

<h2>Modifier franchisé</h2>

<form method="POST">
    <input name="nom" value="<?= $franchise["nom"] ?>"><br><br>
    <input name="email" value="<?= $franchise["email"] ?>"><br><br>
    <input name="ville" value="<?= $franchise["ville"] ?>"><br><br>
    <input name="telephone" value="<?= $franchise["telephone"] ?>"><br><br>
    <button>Enregistrer</button>
</form>

<a href="franchises.php">⬅ Retour</a>

<?php
/* =========================
   DÉTAIL + HISTORIQUE + 4 %
   ========================= */
elseif ($action === "detail" && $id):
    $franchise = Franchise::getById($pdo, $id);
    $ventes = Franchise::getVentes($pdo, $id);

    $totalCA = 0;
    foreach ($ventes as $v) {
        $totalCA += $v["montant"];
    }
    $redevance = $totalCA * 0.04;
?>

<h2>Détail du franchisé</h2>

<p><b>Nom :</b> <?= $franchise["nom"] ?></p>
<p><b>Email :</b> <?= $franchise["email"] ?></p>
<p><b>Date d'entrée :</b> <?= $franchise["date_entree"] ?></p>

<h3>Historique des ventes</h3>

<?php if (count($ventes) === 0): ?>
    <p>Aucune vente enregistrée.</p>
<?php else: ?>
<ul>
    <?php foreach ($ventes as $v): ?>
        <li><?= $v["date_vente"] ?> — <?= $v["montant"] ?> €</li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<p><b>Chiffre d'affaires total :</b> <?= $totalCA ?> €</p>
<p><b>4 % à reverser :</b> <?= $redevance ?> €</p>

<a href="franchises.php">⬅ Retour</a>

<?php endif; ?>

</body>
</html>
