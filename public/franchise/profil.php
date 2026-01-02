<?php
session_start();
require_once "../../config/database.php";
require_once "../../src/models/Franchise.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "franchise") {
    header("Location: ../login.php");
    exit;
}

// Action par défaut
$action = $_GET["action"] ?? "list";
$id = $_GET["id"] ?? null;

$franchise_id = $_SESSION["franchise_id"];

// Modification du profil
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"] ?? null;
    $email = $_POST["email"];
    $password = $_POST["password"];
    $ville = $_POST["ville"];
    $telephone = $_POST["telephone"];

    Franchise::updateProfil($pdo, $email, $nom, $ville, $telephone, $password, $franchise_id);


    header("Location: profil.php");
    exit;
}
// Récupération des infos
$franchise = Franchise::getById($pdo, $franchise_id);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../../includes/navbar.php"; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if ($action === "list"): ?>
            <div class="card shadow">
                <div class="card-header text-center fw-bold">
                    👤 Mon profil
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> <?= htmlspecialchars($franchise["nom"]) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($franchise["email"]) ?></p>
                    <p><strong>Ville :</strong> <?= htmlspecialchars($franchise["ville"]) ?></p>
                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($franchise["telephone"]) ?></p>
                    <p>
                        <strong>Droit d'entrée :</strong>
                        <?php if ($franchise["droit_entree"] === "accepte"): ?>
                            <span class="badge bg-success">Payé</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Non payé</span>
                        <?php endif; ?>
                    </p>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="?action=edit" class="btn btn-primary">
                             Modifier
                        </a>
                        <a href="dashboard.php" class="btn btn-secondary">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- Modifier son Profil -->
            <?php if ($action === "edit"): ?>
            <div class="card shadow">
                <div class="card-header text-center fw-bold">
                     Modifier mon profil
                </div>
                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control"
                                   value="<?= htmlspecialchars($franchise["nom"]) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($franchise["email"]) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control"
                                   value="<?= htmlspecialchars($franchise["ville"]) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control"
                                   value="<?= htmlspecialchars($franchise["telephone"]) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Nouveau mot de passe (optionnel)
                            </label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between">
                            <button class="btn btn-success">
                                 Enregistrer
                            </button>
                            <a href="profil.php" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>