<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Vente.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Recupération des données de vente
$sql = "
SELECT 
    v.date_vente,
    v.nom,
    v.montant,
    f.nom AS franchise
FROM ventes v
JOIN franchises f ON v.franchise_id = f.id
ORDER BY v.date_vente DESC
";

$stmt = $pdo->query($sql);
$ventes = $stmt->fetchAll(PDO::FETCH_ASSOC);


// CALCULS
$totalCA = 0;
$parFranchise = [];

foreach ($ventes as $v) {
    $totalCA += $v['montant'];

    if (!isset($parFranchise[$v['franchise']])) {
        $parFranchise[$v['franchise']] = 0;
    }
    $parFranchise[$v['franchise']] += $v['montant'];
}

$partAdmin = $totalCA * 0.04;
$partFranchises = $totalCA * 0.96;

// HTML DU PDF
$html = '
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
h1, h2 { text-align: center; }
table { width:100%; border-collapse: collapse; margin-top:15px; }
th, td { border:1px solid #333; padding:6px; text-align:center; }
.bar { height:15px; background:#4CAF50; }
.small { font-size:12px; }
</style>

<h1>Rapport des ventes - Driv\'n Cook</h1>

<h2>Chiffre d\'affaires global</h2>
<p><strong>Total :</strong> ' . number_format($totalCA, 2, ',', ' ') . ' €</p>
<p><strong>Part franchiseur (4%) :</strong> ' . number_format($partAdmin, 2, ',', ' ') . ' €</p>
<p><strong>Part franchisés (96%) :</strong> ' . number_format($partFranchises, 2, ',', ' ') . ' €</p>

<h2>Détail des ventes</h2>
<table>
<tr>
    <th>Date</th>
    <th>Nom de la vente</th>
    <th>Franchise</th>
    <th>Montant (€)</th>
</tr>';

foreach ($ventes as $v) {
    $html .= "
    <tr>
        <td>{$v['date_vente']}</td>
        <td>{$v['nom']}</td>
        <td>{$v['franchise']}</td>
        <td>" . number_format($v['montant'], 2, ',', ' ') . "</td>
    </tr>";
}

$html .= '</table>';

// GÉNÉRATION du PDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("rapport_ventes.pdf", ["Attachment" => false]);