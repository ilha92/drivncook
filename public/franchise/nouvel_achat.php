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

// Récupérer les entrepôts AVEC leur prix
$entrepots = $pdo->query("SELECT id, nom, ville, prix FROM entrepots")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $entrepot_id = $_POST["entrepot_id"];
    $franchise_id = $_SESSION["franchise_id"];

    //SÉCURITÉ : récupérer le prix réel depuis la BDD
    $stmt = $pdo->prepare("SELECT prix FROM entrepots WHERE id = ?");
    $stmt->execute([$entrepot_id]);
    $entrepot = $stmt->fetch();

    if (!$entrepot) {
        $message = "Entrepôt invalide.";
    } else {

        $montant = $entrepot["prix"];
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

    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Entrepôt</label>
                            <select name="entrepot_id" id="entrepot" class="form-select" required>
                                <?php foreach ($entrepots as $e): ?>
                                    <option value="<?= $e["id"] ?>" data-prix="<?= $e["prix"] ?>">
                                        <?= htmlspecialchars($e["nom"]) ?> - <?= htmlspecialchars($e["ville"]) ?>
                                        (<?= number_format($e["prix"], 2, ',', ' ') ?> €)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4 text-center">
                            <h5>
                                Montant imposé :
                                <span class="badge bg-primary fs-5">
                                    <span id="prixAffiche">0</span> €
                                </span>
                            </h5>
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

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-dark">
            Retour au dashboard
        </a>
    </div>
</div>

<script>
const select = document.getElementById("entrepot");
const prixAffiche = document.getElementById("prixAffiche");

function updatePrix() {
    const prix = select.options[select.selectedIndex].dataset.prix;
    prixAffiche.textContent = parseFloat(prix).toFixed(2);
}

select.addEventListener("change", updatePrix);
updatePrix();
</script>

</body>
</html>
