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

$franchise_id = $_SESSION["franchise_id"];
$message = "";
$action = $_GET["action"] ?? "list";

// Récup entrepôts
$entrepots = $pdo->query("SELECT id, nom, ville, prix, stock FROM entrepots WHERE actif = 1 AND stock > 0 ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);


// je gere le formulaire d'achat
if ($_SERVER["REQUEST_METHOD"] === "POST" && $action !== "list") {
    $entrepot_id = (int)$_POST["entrepot_id"];
    
    $stmt = $pdo->prepare("
        SELECT 1 FROM achats 
        WHERE franchise_id = ? AND entrepot_id = ?
    ");
    $stmt->execute([$franchise_id, $entrepot_id]);
    if ($stmt->fetch()) {
        $message = "Cet entrepôt est déjà acheté par votre franchise.";
    } else {
        $stmt = $pdo->prepare("
            SELECT prix FROM entrepots 
            WHERE id = ? AND actif = 1 AND stock > 0
            FOR UPDATE
        ");
        $stmt->execute([$entrepot_id]);
        $entrepot = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($entrepot) {
            $pdo->beginTransaction();
            try {
                $montant = $entrepot["prix"];
                $pourcentage = 80;
                
                $stmt = $pdo->prepare("
                    INSERT INTO achats (franchise_id, entrepot_id, montant_total, pourcentage_entrepot, date_achat)
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$franchise_id, $entrepot_id, $montant, $pourcentage]);
                
                $stmt = $pdo->prepare("UPDATE entrepots SET stock = stock - 1 WHERE id = ?");
                $stmt->execute([$entrepot_id]);
                
                $pdo->commit();
                $message = "Achat enregistré ! Montant: " . number_format($montant, 2, ',', ' ') . "€";
            } catch (Exception $e) {
                $pdo->rollBack();
                $message = "Erreur: " . $e->getMessage();
            }
        } else {
            $message = "Entrepôt épuisé ou invalide.";
        }
    }
}
// Récup achats franchise
$sql = "
SELECT 
    achats.id, achats.date_achat, achats.montant_total, achats.pourcentage_entrepot,
    entrepots.nom AS nom_entrepot
FROM achats
LEFT JOIN entrepots ON achats.entrepot_id = entrepots.id
WHERE achats.franchise_id = ?
ORDER BY achats.date_achat DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$franchise_id]);
$achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mes achats</title>
</head>
<body>
<?php include "../../includes/navbar.php"; ?>

<div class="container mt-5">
    
    <!-- TABS -->
    <ul class="nav nav-tabs mb-4" id="achatsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $action === 'list' ? 'active' : '' ?>" 
                    id="liste-tab" data-bs-toggle="tab" data-bs-target="#liste" 
                    type="button">Liste achats (<?= count($achats) ?>)</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $action !== 'list' ? 'active' : '' ?>" 
                    id="nouveau-tab" data-bs-toggle="tab" data-bs-target="#nouveau" 
                    type="button">Nouvel achat</button>
        </li>
    </ul>

    <!-- CONTENU TABS -->
    <div class="tab-content" id="achatsTabsContent">
        
        <!-- TAB 1 : LISTE -->
        <div class="tab-pane fade <?= $action === 'list' ? 'show active' : '' ?>" 
             id="liste" role="tabpanel">
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <?php if (empty($achats)): ?>
                <div class="alert alert-info text-center">
                    Aucun achat enregistré. <a href="?action=nouveau" class="alert-link">Faire le premier ?</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Entrepôt</th>
                                <th>Montant</th>
                                <th>% Entrepôt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($achats as $achat): ?>
                                <tr>
                                    <td><?= $achat["date_achat"] ?></td>
                                    <td><?= $achat["nom_entrepot"] ?></td>
                                    <td><?= $achat["montant_total"] ?></td>
                                    <td><?= $achat["pourcentage_entrepot"] ?> %</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- TAB 2 : NOUVEL ACHAT -->
        <div class="tab-pane fade <?= $action !== 'list' ? 'show active' : '' ?>" 
             id="nouveau" role="tabpanel">
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-success text-center"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-4">Nouvel achat</h5>
                            
                            <form method="POST" action="?action=nouveau">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Choisir entrepôt</label>
                                    <select name="entrepot_id" id="entrepot" class="form-select form-select-lg" required>
                                        <option value="">Sélectionner...</option>
                                        <?php foreach ($entrepots as $e): ?>
                                            <option value="<?= $e["id"] ?>" data-prix="<?= $e["prix"] ?>">
                                                <?= htmlspecialchars($e["nom"]) ?> - <?= htmlspecialchars($e["ville"]) ?>
                                                (<?= number_format($e["prix"], 2, ',', ' ') ?> €| <?= $e["stock"] ?> restants)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="text-center mb-4">
                                    <h4 class="text-primary">
                                        Montant imposé : 
                                        <span class="badge bg-success fs-3 px-3 py-2" id="prixAffiche">0 €</span>
                                    </h4>
                                    <small class="text-muted">80% versé à l'entrepôt</small>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-cart-plus"></i> Valider achat
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-5">
        <a href="dashboard.php" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left"></i> Retour dashboard
        </a>
    </div>
</div>

<script>
const select = document.getElementById('entrepot');
const prixSpan = document.getElementById('prixAffiche');

function updatePrix() {
    const option = select.options[select.selectedIndex];
    const prix = option.dataset.prix;
    if (prix) {
        prixSpan.textContent = parseFloat(prix).toLocaleString('fr-FR', { 
            minimumFractionDigits: 2, maximumFractionDigits: 2 
        }) + ' €';
    } else {
        prixSpan.textContent = '0 €';
    }
}

select.addEventListener('change', updatePrix);
updatePrix();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
