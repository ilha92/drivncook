<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Vente.php";


if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Récupérer toutes les ventes
$ventes = Vente::getAll($pdo);

// Calcul du CA par franchisé
$caParFranchise = [];

foreach ($ventes as $v) {
    $nom = $v["franchise"];
    if (!isset($caParFranchise[$nom])) {
        $caParFranchise[$nom] = 0;
    }
    $caParFranchise[$nom] += $v["montant"];
}
// Préparation des données pour JS
$labels = array_keys($caParFranchise);
$values = array_values($caParFranchise);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Ventes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
</head>
<script>
function exportPDF() {
    const { jsPDF } = window.jspdf;

    html2canvas(document.getElementById("chartCA")).then(canvas => {
        const imgData = canvas.toDataURL("image/png");

        const pdf = new jsPDF("p", "mm", "a4");
        const pageWidth = pdf.internal.pageSize.getWidth();

        const imgWidth = pageWidth - 20;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        pdf.setFontSize(16);
        pdf.text("Rapport des ventes - Driv'n Cook", 10, 15);

        pdf.addImage(imgData, "PNG", 10, 25, imgWidth, imgHeight);
        pdf.save("rapport_ventes.pdf");
    });
}
</script>

<body>

<h1>Analyse des ventes</h1>

<!-- ======================
     HISTORIQUE DES VENTES
====================== -->
<h2>Historique des ventes</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Date</th>
    <th>Franchisé</th>
    <th>Montant (€)</th>
</tr>

<?php foreach ($ventes as $v): ?>
<tr>
    <td><?= htmlspecialchars($v["date_vente"]) ?></td>
    <td><?= htmlspecialchars($v["franchise"]) ?></td>
    <td><?= number_format($v["montant"], 2, ',', ' ') ?> €</td>
</tr>
<?php endforeach; ?>
</table>

<br><hr><br>

<!-- ======================
     CA & REVERSEMENTS
====================== -->
<h2>Chiffre d'affaires & reversements (4%)</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Franchisé</th>
    <th>CA total (€)</th>
    <th>4% à reverser (€)</th>
</tr>

<?php foreach ($caParFranchise as $franchise => $ca): ?>
<tr>
    <td><?= htmlspecialchars($franchise) ?></td>
    <td><?= number_format($ca, 2, ',', ' ') ?> €</td>
    <td><?= number_format($ca * 0.04, 2, ',', ' ') ?> €</td>
</tr>
<?php endforeach; ?>
</table>
<div style="width: 400px; height: 300px; margin: 0 auto;">
    <canvas id="chartCA"></canvas>
</div>
<script>
const data = {
    labels: <?= json_encode(array_keys($caParFranchise)) ?>,
    datasets: [{
        label: 'Chiffre d\'affaires',
        data: <?= json_encode(array_values($caParFranchise)) ?>,
        backgroundColor: [
            '#4CAF50',
            '#2196F3',
            '#FFC107',
            '#E91E63',
            '#9C27B0'
        ]
    }]
};

const total = data.datasets[0].data.reduce((a, b) => a + b, 0);

new Chart(document.getElementById('chartCA'), {
    type: 'pie',
    data: data,
    plugins: [ChartDataLabels],
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            datalabels: {
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: (value, ctx) => {
                    let percent = (value / total * 100).toFixed(1);
                    return percent + " %";
                }
            }
        }
    }
});
</script>

<button onclick="exportPDF()">📄 Exporter le graphique en PDF</button>

<a href="../../pdf/ventes_pdf.php" target="_blank">📄 Générer PDF des ventes</a>
<br>
<a href="dashboard.php">⬅ Retour admin</a>

</body>
</html>
