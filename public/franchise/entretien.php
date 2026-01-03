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

$camion_id = $_GET["id"];

// Ajouter un entretien
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $date_entretien = $_POST["date_entretien"];
    $nom = $_POST["nom"];
    $description = $_POST["description"];
    

    $sql = "INSERT INTO entretiens (camion_id, date_entretien, nom, description)
            VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$camion_id, $date_entretien, $nom, $description]);
}

// Récupérer les entretiens
$sql = "SELECT * FROM entretiens WHERE camion_id = ? ORDER BY date_entretien DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$camion_id]);
$entretiens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carnet d'entretien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar.php"; ?>

<div class="container mt-5">

    <h1 class="text-center mb-4">Carnet d’entretien</h1>

    <!-- FORMULAIRE AJOUT ENTRETIEN -->
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow mb-5">
                <div class="card-body">
                    <h4 class="mb-4"> Ajouter un entretien</h4>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Date de l'entretien</label>
                            <input type="date" name="date_entretien" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom de l'entretien</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex : Vidange, freins…" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                 Ajouter
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- HISTORIQUE -->
    <h2 class="text-center mb-4">Historique des entretiens</h2>

    <?php if (count($entretiens) == 0): ?>
        <div class="alert alert-info text-center">
            Aucun entretien enregistré.
        </div>
    <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <table class="table table-striped table-bordered align-middle text-center shadow">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Nom de l'entretien</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entretiens as $entretien): ?>
                            <tr>
                                <td><?= $entretien["date_entretien"] ?></td>
                                <td><?= htmlspecialchars($entretien["nom"]) ?></td>
                                <td><?= htmlspecialchars($entretien["description"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- RETOUR -->
    <div class="text-center mt-4">
        <a href="camions.php" class="btn btn-outline-dark">
            ⬅ Retour
        </a>
    </div>

</div>

</body>
</html>