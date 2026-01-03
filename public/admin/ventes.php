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
<h1>Analyse des ventes</h1>

<h2>Historique des ventes</h2>
<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Franchisé</th>
    <th>Montant (€)</th>
</tr>
<?php foreach ($ventes as $v): ?>
<tr>
    <td><?= $v["date_vente"] ?></td>
    <td><?= $v["franchise"] ?></td>
    <td><?= number_format($v["montant"], 2, ',', ' ') ?> €</td>
</tr>
<?php endforeach; ?>
</table>

<h2>Chiffre d'affaires & répartition</h2>

<div style="width: 450px; margin:auto;">
    <canvas id="chartCA"></canvas>
</div>

<br>
<a href="../../pdf/ventes_pdf.php" target="_blank">📄 Générer PDF des ventes</a>
<br> <br>
<button  onclick="exportPDF()">📄 Exporter le graphique en PDF</button>
<br><br>
<a href="dashboard.php">⬅ Retour admin</a>

</body>
</html>
