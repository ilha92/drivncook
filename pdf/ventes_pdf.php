<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Vente.php';

use Dompdf\Dompdf;

// Récupération des ventes
$ventes = Vente::getAll($pdo);
$totalCA = Vente::getCATotal($pdo);

// HTML du PDF
$html = '
<h2 style="text-align:center;">Rapport des ventes</h2>
<table border="1" width="100%" cellpadding="5" cellspacing="0">
<tr>
    <th>Date</th>
    <th>Franchise</th>
    <th>Montant (€)</th>
</tr>';

$total = 0;

foreach ($ventes as $v) {
    $html .= "
    <tr>
        <td>{$v['date_vente']}</td>
        <td>{$v['franchise']}</td>
        <td>{$v['montant']} €</td>
    </tr>";
    $total += $v['montant'];
}

$html .= "
<tr>
    <td colspan='2'><strong>Total du chiffre d'affaires</strong></td>
    <td><strong>{$totalCA} €</strong></td>
</tr>
</table>
";

// Génération PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("rapport_ventes.pdf", ["Attachment" => false]);
exit;
