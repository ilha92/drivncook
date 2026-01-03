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

    $message = "Achat enregistré avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvel achat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../../includes/navbar.php"; ?>
<div class="container mt-5">
    <h1 class="text-center mb-4">Nouvel achat</h1>
    <!-- Affichage message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- FORMULAIRE -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Montant total (€)</label>
                            <input type="number" name="montant" step="0.01" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Entrepôt</label>
                            <select name="entrepot" class="form-select" required>
                                <?php foreach ($entrepots as $entrepot): ?>
                                    <option value="<?= $entrepot["id"] ?>">
                                        <?= htmlspecialchars($entrepot["nom"]) ?> - <?= htmlspecialchars($entrepot["ville"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                 Valider l'achat
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- RETOUR -->
    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-dark">
             Retour au dashboard
        </a>
    </div>
</div>
</body>
</html>