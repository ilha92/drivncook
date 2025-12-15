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
        $message = "Cet email est déjà utilisé ❌";
    } else {
        // Hash du mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insérer le nouvel utilisateur
        $sql = "INSERT INTO franchises (nom, email, mot_de_passe, ville, telephone, date_creation)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$nom, $email, $passwordHash, $ville, $telephone, $date_creation])) {
            // Inscription réussie → redirection vers login.php
            header("Location: /public/login.php");
            exit;
            
            $message = "Inscription réussie ✅";
            
        } else {
            $message = "Erreur lors de l'inscription ❌";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription franchisé</title>
</head>
<body>

<h1>Inscription franchisé</h1>

<form method="POST" action="">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <label>Ville :</label><br>
    <input type="text" name="ville" required><br><br>

    <label>Téléphone :</label><br>
    <input type="text" name="telephone" required><br><br>   

       <!-- Affichage du message -->
    <?php if(!empty($message)): ?>
        <p style="color: red; font-weight: bold;"><?php echo $message; ?></p>
    <?php endif; ?>
<br>
    <button type="submit">S'inscrire</button>
</form>

</body>
</html>
