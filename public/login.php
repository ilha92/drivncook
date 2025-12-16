<?php
session_start();
require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    /* =========================
       1) Vérification ADMIN
       ========================= */

    $sqlAdmin = "SELECT * FROM admins WHERE email = ?";
    $stmtAdmin = $pdo->prepare($sqlAdmin);
    $stmtAdmin->execute([$email]);
    $admin = $stmtAdmin->fetch();

    if ($admin && password_verify($password, $admin["mot_de_passe"])) {

        $_SESSION["type"] = "admin";
        $_SESSION["email"] = $admin["email"];

        header("Location: index.php");
        exit;
    }

    /* =========================
       2) Vérification FRANCHISÉ
       ========================= */

    $sqlFranchise = "SELECT * FROM franchises WHERE email = ?";
    $stmtFranchise = $pdo->prepare($sqlFranchise);
    $stmtFranchise->execute([$email]);
    $franchise = $stmtFranchise->fetch();

if ($franchise && password_verify($password, $franchise["mot_de_passe"])) {

    $_SESSION["type"] = "franchise";
    $_SESSION["email"] = $franchise["email"];
    $_SESSION["franchise_id"] = $franchise["id"];

    header("Location: index.php");
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
</head>
<body>

<h1>Connexion</h1>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<form method="POST" action="">
    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

<a href="register.php">Inscription franchisé</a>

</body>
</html>
