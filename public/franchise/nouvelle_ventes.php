<?php
session_start();
require_once "../../config/database.php";

// Sécurité
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $montant = $_POST["montant"];
    $franchise_id = $_SESSION["franchise_id"];

    $sql = "INSERT INTO ventes (franchise_id, montant)
            VALUES (?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$franchise_id, $montant]);

    $message = "Vente enregistrée avec succès 💰";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle vente</title>
</head>
<body>

<h1>Nouvelle vente</h1>

<?php if ($message != "") echo "<p>$message</p>"; ?>

<form method="POST">
    <label>Montant de la vente (€)</label><br>
    <input type="number" name="montant" step="0.01" required><br><br>

    <button type="submit">Enregistrer la vente</button>
</form>

<br>
<a href="dashboard.php">⬅ Retour au dashboard</a>

</body>
</html>
