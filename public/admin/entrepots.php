<?php
session_start();
require_once "../../config/database.php";

// Sécurité admin
if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$message = "";

// AJOUT ENTREPÔT
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $ville = $_POST["ville"];
    $prix = $_POST["prix"];

    $stmt = $pdo->prepare(
        "INSERT INTO entrepots (nom, ville, prix) VALUES (?, ?, ?)"
    );
    $stmt->execute([$nom, $ville, $prix]);

    $message = "Entrepôt ajouté avec succès.";
}

// LISTE
$entrepots = $pdo->query("SELECT * FROM entrepots")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des entrepôts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar_admin.php"; ?>

<div class="container mt-5">
    <h1 class="mb-4">Gestion des entrepôts</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <!-- FORM AJOUT -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5>Ajouter un entrepôt</h5>

            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input class="form-control" name="nom" placeholder="Nom" required>
                </div>
                <div class="col-md-4">
                    <input class="form-control" name="ville" placeholder="Ville" required>
                </div>
                <div class="col-md-4">
                    <input class="form-control" name="prix" type="number" step="0.01" placeholder="Prix (€)" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLEAU -->
    <div class="card shadow">
        <div class="card-body">
            <h5>Liste des entrepôts</h5>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Prix (€)</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entrepots as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e["nom"]) ?></td>
                            <td><?= htmlspecialchars($e["ville"]) ?></td>
                            <td><?= number_format($e["prix"], 2, ',', ' ') ?> €</td>
                            <td>
                                <?= $e["actif"] ? 'Actif' : 'Désactivé' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</body>
</html>
