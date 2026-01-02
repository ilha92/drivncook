<?php
session_start();
require_once "../../config/database.php";
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

$message = "";

// Récupérer les entrepôts
$entrepots = $pdo->query("SELECT * FROM entrepots")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $montant = $_POST["montant"];
    $entrepot_id = $_POST["entrepot"];
    $franchise_id = $_SESSION["franchise_id"];

    // règle simple imposée
    $pourcentage_entrepot = 80;

    $sql = "INSERT INTO achats (franchise_id, entrepot_id, montant_total, pourcentage_entrepot)
            VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $franchise_id,
        $entrepot_id,
        $montant,
        $pourcentage_entrepot
    ]);

    $message = "Achat enregistré avec succès ✅";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Nouvel achat</title>
</head>
<body>
<?php include "../../includes/navbar.php"; ?>
<h1>Nouvel achat</h1>

<?php if ($message != "") echo "<p>$message</p>"; ?>

<form method="POST">

    <label>Montant total (€)</label><br>
    <input type="number" name="montant" step="0.01" required><br><br>

    <label>Entrepôt</label><br>
    <select name="entrepot" required>
        <?php foreach ($entrepots as $entrepot): ?>
            <option value="<?= $entrepot["id"] ?>">
                <?= $entrepot["nom"] ?> - <?= $entrepot["ville"] ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Valider l'achat</button>

</form>

<br>
<a href="dashboard.php">⬅ Retour au dashboard</a>

</body>
</html>
