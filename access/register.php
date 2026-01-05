<?php
require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $ville = $_POST["ville"];
    $telephone = $_POST["telephone"];
    $date_creation = date('Y-m-d H:i:s');

    // Vérifier si l'email existe déjà
    $sql = "SELECT * FROM franchises WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // L'email existe déjà
        $message = "Cet email est déjà utilisé ";
    } else {
        // Hash du mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);


        //id	nom	email	mot_de_passe	ville	telephone	date_entree, droit_entree, created_at
        // Insérer le nouvel utilisateur
        $sql = "INSERT INTO franchises (nom, email, mot_de_passe, ville, telephone, date_entree, droit_entree, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$nom, $email, $passwordHash, $ville, $telephone, $date_creation, 0, $date_creation])) {
            // Inscription réussie → redirection vers login.php
            header("Location: login.php");
            exit;
            
            $message = "Inscription réussie";
            
        } else {
            $message = "Erreur lors de l'inscription";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription franchisé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Inscription franchisé</h3>
                          <?php if ($message): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($message) ?>
                        </div>
                         <?php endif; ?>
                     <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nom de la franchise</label>
                            <input type="text" name="nom" class="form-control" placeholder="Nom de votre franchise" required>
                        </div>
        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Votre email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" placeholder="****************" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control" placeholder="Mettre votre ville" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" placeholder="Numéro de téléphone" required>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-success">Créer mon compte</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">  
                        <a href="login.php">Déjà un compte ? Connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>