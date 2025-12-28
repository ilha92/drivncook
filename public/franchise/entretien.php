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

$camion_id = $_GET["id"];

// Ajouter un entretien
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $description = $_POST["description"];

    $sql = "INSERT INTO entretiens (camion_id, description)
            VALUES (?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$camion_id, $description]);
}

// Récupérer les entretiens
$sql = "SELECT * FROM entretiens WHERE camion_id = ? ORDER BY date_entretien DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$camion_id]);
$entretiens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carnet d'entretien</title>
</head>
<body>

<h1>Carnet d'entretien</h1>

<form method="POST">
    <label>Nouvel entretien</label><br>
    <textarea name="description" required></textarea><br><br>

    <button type="submit">Ajouter</button>
</form>

<hr>

<h2>Historique</h2>

<?php if (count($entretiens) == 0): ?>
    <p>Aucun entretien enregistré.</p>
<?php else: ?>
    <ul>
        <?php foreach ($entretiens as $entretien): ?>
            <li>
                <?= $entretien["date_entretien"] ?> – <?= $entretien["description"] ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<br>
<a href="camions.php">⬅ Retour</a>

</body>
</html>
