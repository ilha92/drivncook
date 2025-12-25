<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ .'/../config/database.php';
require_once __DIR__ .'/../src/models/Franchise.php';

use Dompdf\Dompdf;

// Récupération des franchises
$franchises = Franchise::getAll($pdo);

// HTML du PDF
$html = '
<h2 style="text-align:center;">Liste des franchises</h2>
<table border="1" width="100%" cellpadding="5" cellspacing="0">
<tr>
    <th>Nom</th>
    <th>Email</th>
    <th>Ville</th>
    <th>Téléphone</th>
    <th>Date d\'entrée</th>
    <th>Droit d\'entrée</th>
</tr>';

foreach ($franchises as $franchise) {
    $droit_entree = $franchise['droit_entree'] ? 'Payé' :'';   
    $html .= "
    <tr>
        <td>{$franchise['nom']}</td>
        <td>{$franchise['email']}</td>
        <td>{$franchise['ville']}</td>
        <td>{$franchise['telephone']}</td>
        <td>{$franchise['date_entree']}</td>
        <td>{$droit_entree}</td>
    </tr>";
}
$html .= '</table>';

// Génération PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("rapport_franchises.pdf", ["Attachment" => false]);
exit;

