<?php
session_start();
require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sqlAdmin = "SELECT * FROM admins WHERE email = ?";
    $stmtAdmin = $pdo->prepare($sqlAdmin);
    $stmtAdmin->execute([$email]);
    $admin = $stmtAdmin->fetch();

    if ($admin && password_verify($password, $admin["mot_de_passe"])) {

        $_SESSION["type"] = "admin";
        $_SESSION["email"] = $admin["email"];

        header("Location: ../public/index.php");
        exit;
    }

    $sqlFranchise = "SELECT * FROM franchises WHERE email = ?";
    $stmtFranchise = $pdo->prepare($sqlFranchise);
    $stmtFranchise->execute([$email]);
    $franchise = $stmtFranchise->fetch();

if ($franchise && password_verify($password, $franchise["mot_de_passe"])) {

    $_SESSION["type"] = "franchise";
    $_SESSION["email"] = $franchise["email"];
    $_SESSION["nom"] = $franchise["nom"];
    $_SESSION["franchise_id"] = $franchise["id"];
    $_SESSION["droit_entree"] = $franchise["droit_entree"];

    header("Location: ../public/index.php");
    exit;
}

    // Si aucun des deux ne correspond
    $message = "Email ou mot de passe incorrect ";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Connexion</h3>

                    <?php if ($message): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Votre email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" placeholder="****************" required>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary">Se connecter</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="register.php">Créer un compte franchisé</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>