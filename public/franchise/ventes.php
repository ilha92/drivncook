<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Vente.php";
// Sécurité : uniquement franchisé
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

// Vérification droit d'entrée
if ($_SESSION["droit_entree"] !== "accepte") {
    header("Location: droit_entree.php");
    exit;
}


// Ajout d'une vente
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Vente::add(
        $pdo,
        $_SESSION["franchise_id"],
        $_POST["date_vente"],
        $_POST["nom"],
        $_POST["montant"]
    );
    header("Location: ventes.php");
    exit;
}

// Historique des ventes
$ventes = Vente::getByFranchise($pdo, $_SESSION["franchise_id"]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mes ventes</title>
</head>
<body>
<?php include "../../includes/navbar.php"; ?>
<h1>Mes ventes</h1>

<!-- AJOUT VENTE -->
<h2>Ajouter une vente</h2>
<form method="POST">
    <label>Date de la vente :</label><br>
    <input type="date" name="date_vente" required><br><br>
    <label for="text">Nom de la vente :</label><br>
    <input type="text" name="nom" required><br><br>
    <label>Montant (€) :</label><br>
    <input type="number" step="0.01" name="montant" required><br><br>

    <button>Ajouter</button>
</form>

<hr>

<!-- HISTORIQUE -->
<h2>Historique des ventes</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Nom</th>
    <th>Montant (€)</th>
</tr>

<?php foreach ($ventes as $v): ?>
<tr>
    <td><?= $v["date_vente"] ?></td>
    <td><?= $v["nom"] ?></td>
    <td><?= number_format($v["montant"], 2, ',', ' ') ?> €</td>
</tr>
<?php endforeach; ?>
</table>
<a href="../../pdf/ventes_pdf.php" target="_blank">📄 Générer PDF des ventes</a>
<br><br>
<a href="dashboard.php">⬅ Retour dashboard</a>

</body>
</html>
