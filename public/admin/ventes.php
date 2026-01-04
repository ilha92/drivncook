<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Vente.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$ventes = Vente::getAll($pdo);

// Calcul CA par franchise
$caParFranchise = [];
foreach ($ventes as $v) {
    $nom = $v["franchise"];
    if (!isset($caParFranchise[$nom])) {
        $caParFranchise[$nom] = 0;
    }
    $caParFranchise[$nom] += $v["montant"];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - Ventes</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="../../assets/js/chart.js" defer></script>

    <!-- JS externe -->
    <script>
        const chartLabels = <?= json_encode(array_keys($caParFranchise)) ?>;
        const chartData = <?= json_encode(array_values($caParFranchise)) ?>;
    </script>

</head>

<body>
<?php include "../../includes/navbar_admin.php"; ?>
<div class="container-xl mt-5">
    <h1 class="mb-4 text-center">Analyse des ventes</h1>
    <div class="card shadow mb-5">
        <div class="card-body">
            <h4 class="mb-3">Historique des ventes</h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Franchisé</th>
                            <th class="text-end">Montant (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventes as $v): ?>
                        <tr>
                            <td><?= htmlspecialchars($v["date_vente"]) ?></td>
                            <td><?= htmlspecialchars($v["franchise"]) ?></td>
                            <td class="text-end fw-bold">
                                <?= number_format($v["montant"], 2, ',', ' ') ?> €
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- =========================
         GRAPHIQUE
    ========================== -->
    <div class="card shadow mb-4">
        <div class="card-body text-center">
            <h4 class="mb-3">Répartition du chiffre d'affaires</h4>

            <div class="mx-auto" style="max-width: 450px;">
                <canvas id="chartCA"></canvas>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button onclick="exportPDF()" class="btn btn-outline-primary">
                    Exporter le graphique en PDF
                </button>

                <a href="../../pdf/ventes_pdf.php" target="_blank" class="btn btn-outline-secondary">
                    PDF complet des ventes
                </a>
            </div>
        </div>
    </div>
    <div class="text-center">
        <a href="dashboard.php" class="btn btn-outline-dark">
            Retour admin
        </a>
    </div>

</div>

</body>

</html>