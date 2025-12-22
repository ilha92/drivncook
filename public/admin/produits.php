<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Produit.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Action par défaut
$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;

/* =======================
   AJOUT PRODUIT
======================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Produit::create(
        $pdo,
        $_POST["nom"],
        $_POST["stock"],
        $_POST["entrepot_id"]
    );
    header("Location: produits.php");
    exit;
}

if ($action === "edit" && $id && $_SERVER["REQUEST_METHOD"] === "POST") {
    Produit::updateByAdmin(
        $pdo,
        $_POST["nom"],
        $_POST["stock"],
        $_POST["entrepot_id"],
        $id
    );
    header("Location: produits.php");
    exit;
}

if ($action === "delete" && $id) {
    Produit::delete($pdo, $id);
    header("Location: produits.php");
    exit;
}

$produits = Produit::getAll($pdo);
$entrepots = Produit::getEntrepots($pdo);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Produits</title>
</head>
<body>

<h1>Gestion des produits</h1>

<!-- =======================
     AJOUT PRODUIT
======================= -->
<h2>Ajouter un produit</h2>

<form method="POST">
    <input name="nom" placeholder="Nom du produit" required><br><br>
    <input type="number" name="stock" placeholder="Stock" min="0" required><br><br>

    <select name="entrepot_id" required>
        <?php foreach ($entrepots as $e): ?>
            <option value="<?= $e["id"] ?>"><?= $e["nom"] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button>Créer</button>
</form>

<hr>

<!-- =======================
     LISTE PRODUITS
======================= -->
<h2>Stocks disponibles</h2>

<?php if ($action === "list"): 
$produits = Produit::getAll($pdo);
endif; ?>

<table border="1" cellpadding="5">
<tr>
    <th>Produit</th>
    <th>Stock</th>
    <th>Entrepôt</th>
    <th>Action</th>
</tr>

<?php foreach ($produits as $p): ?>
<tr>
    <td><?= $p["nom"] ?></td>
    <td><?= $p["stock"] ?></td>
    <td><?= $p["entrepot"] ?></td>
    <td><a href="?action=edit&id=<?= $p["id"] ?>">✏️</a> 
        <a href="?action=delete&id=<?= $p["id"] ?>"
           onclick="return confirm('Supprimer ce produit ?')">🗑️</a></td>
</tr>
<?php endforeach; ?>
<!-- =======================
     MODIFICATION
======================= -->
<?php if ($action === "edit" && $id): 
$produits = Produit::getById($pdo, $id);
?>

<h2>Modifier le produit</h2>

<form method="POST">
    <input name="nom" value="<?= htmlspecialchars($produits["nom"]) ?>"><br><br>
    <input name="stock" value="<?= htmlspecialchars($produits["stock"]) ?>"><br><br>
    <input name="entrepot_id" value="<?= htmlspecialchars($produits["entrepot_id"]) ?>"><br><br>
    <button>Enregistrer</button>
</form>

<a href="produits.php">⬅ Retour</a>

<?php endif; ?>

</table>

<a href="dashboard.php">⬅ Retour admin</a>

</body>
</html>
