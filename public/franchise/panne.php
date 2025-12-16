<?php
session_start();
require_once "../../config/database.php";

// Sécurité
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

$camion_id = $_GET["id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $description = $_POST["description"];

    $sql = "INSERT INTO pannes (camion_id, description)
            VALUES (?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$camion_id, $description]);

    // Mettre le camion en panne
    $pdo->prepare("UPDATE camions SET statut = 'En panne' WHERE id = ?")
        ->execute([$camion_id]);

    $message = "Panne déclarée 🚨";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déclarer une panne</title>
</head>
<body>

<h1>Déclarer une panne</h1>

<?php if ($message != "") echo "<p>$message</p>"; ?>

<form method="POST">
    <label>Description de la panne</label><br>
    <textarea name="description" required></textarea><br><br>

    <button type="submit">Déclarer</button>
</form>

<br>
<a href="camions.php">⬅ Retour</a>

</body>
</html>
