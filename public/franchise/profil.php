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
</head>
<body>

<h1>Mon profil</h1>
<?php if ($action === "list"): 
$franchises = Franchise::getAll($pdo);
?>
<p><strong>Nom :</strong> <?= $franchise["nom"] ?></p>
<p><strong>Email :</strong> <?= $franchise["email"] ?></p>
<p><strong>Droit d'entrée :</strong> <?= $franchise["droit_entree"] === 'accepte' ? ' Payé' : 'Non payé' ?></p>
<p><strong>Ville :</strong> <?= $franchise["ville"] ?></p>
<p><strong>Téléphone :</strong> <?= $franchise["telephone"] ?></p>

 <a href="?action=edit&id=<?= $franchise["id"] ?>">Modifier mes informations</a><br><br>
<a href="dashboard.php">Retour</a>
<?php endif; ?>

<!-- j'ai mis un id dans le lien mais c'est pas utile car on edite que son propre profil -->
<?php if ($action === "edit" && $id): 
$franchise = Franchise::getById($pdo, $id);
?>

<h2>Modifier le franchisé</h2>

<form method="POST">
    <label>Nom de la franchise :</label><br>
    <input name="nom" placeholder="Nom de la franchise" value="<?= htmlspecialchars($franchise["nom"]) ?>"><br><br>
    <input name="email" placeholder="Email" value="<?= htmlspecialchars($franchise["email"]) ?>"><br><br>
    <label>Changer votre mot de passe (laisser vide pour ne pas changer) :</label><br>
    <input type="password" name="password" value="" placeholder="Mot de passe"><br><br>
    <input name="ville" placeholder="Ville" value="<?= htmlspecialchars($franchise["ville"]) ?>"><br><br>
    <input name="telephone" placeholder="Téléphone" value="<?= htmlspecialchars($franchise["telephone"]) ?>"><br><br>
    <button>Enregistrer</button>
</form>

<a href="profil.php">⬅ Retour</a>

<?php endif; ?>

</body>
</html>
