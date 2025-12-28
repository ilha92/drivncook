<?php
session_start();
require_once "../../config/database.php";

if ($_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST["payer"])) {
    $stmt = $pdo->prepare("
        UPDATE franchises 
        SET droit_entree = 'accepte'
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION["franchise_id"]]);

    // Met à jour la session
    $_SESSION["droit_entree"] = 'accepte';

    header("Location: dashboard.php");
    exit;
}
?>

<h1>Droit d'entrée</h1>

<p>Pour accéder à la plateforme, vous devez régler le droit d’entrée de :</p>
<h2>50 000 €</h2>

<form method="POST">
    <button name="payer">💳 Payer maintenant</button>
</form>
    <a href="../index.php">⬅ Retour</a>
