<?php
// public/admin.php — dashboard administrateur
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/User.php';
require_once __DIR__ . '/../src/models/Franchise.php';
require_once __DIR__ . '/../src/models/Camion.php';

// Vérifier l'accès admin
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /public/login.php');
    exit;
}

// Actions de suppression
if (!empty($_GET['delete_user'])) {
    User::delete($_GET['delete_user']);
    header('Location: /public/admin.php');
    exit;
}
if (!empty($_GET['delete_franchise'])) {
    Franchise::delete($_GET['delete_franchise']);
    header('Location: /public/admin.php');
    exit;
}
if (!empty($_GET['delete_camion'])) {
    Camion::delete($_GET['delete_camion']);
    header('Location: /public/admin.php');
    exit;
}

$users = User::all();
$franchises = Franchise::all();
$camions = Camion::all();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Admin — Driv'n Cook</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <script>
    function confirmDelete(kind, id) {
      return confirm('Supprimer ' + kind + ' #' + id + ' ?');
    }
  </script>
</head>
<body>
  <h1>Administration</h1>
  <p><a href="/public/logout.php">Se déconnecter</a></p>

  <section>
    <h2>Utilisateurs</h2>
    <table border="1" cellpadding="6">
      <tr><th>ID</th><th>Nom</th><th>Email</th><th>Role</th><th>Actions</th></tr>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?=htmlspecialchars($u['id'])?></td>
        <td><?=htmlspecialchars($u['nom'])?></td>
        <td><?=htmlspecialchars($u['email'])?></td>
        <td><?=htmlspecialchars($u['role'])?></td>
        <td>
          <?php if ($u['role'] !== 'admin'): ?>
            <a href="/public/admin.php?delete_user=<?=htmlspecialchars($u['id'])?>" onclick="return confirmDelete('utilisateur', <?=htmlspecialchars($u['id'])?>)">Supprimer</a>
          <?php else: ?>
            (admin)
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </section>

  <section>
    <h2>Franchises</h2>
    <table border="1" cellpadding="6">
      <tr><th>ID</th><th>User ID</th><th>Ville</th><th>Téléphone</th><th>CA</th><th>Actions</th></tr>
      <?php foreach ($franchises as $f): ?>
      <tr>
        <td><?=htmlspecialchars($f['id'])?></td>
        <td><?=htmlspecialchars($f['user_id'])?></td>
        <td><?=htmlspecialchars($f['ville'])?></td>
        <td><?=htmlspecialchars($f['telephone'])?></td>
        <td><?=htmlspecialchars($f['chiffre_affaires'])?></td>
        <td>
          <a href="/public/franchise_edit.php?id=<?=htmlspecialchars($f['id'])?>">Modifier</a>
          <a href="/public/admin.php?delete_franchise=<?=htmlspecialchars($f['id'])?>" onclick="return confirmDelete('franchise', <?=htmlspecialchars($f['id'])?>)">Supprimer</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </section>

  <section>
    <h2>Camions</h2>
    <table border="1" cellpadding="6">
      <tr><th>ID</th><th>Numéro</th><th>État</th><th>Emplacement</th><th>Franchise</th><th>Actions</th></tr>
      <?php foreach ($camions as $c): ?>
      <tr>
        <td><?=htmlspecialchars($c['id'])?></td>
        <td><?=htmlspecialchars($c['numero_camion'])?></td>
        <td><?=htmlspecialchars($c['etat'])?></td>
        <td><?=htmlspecialchars($c['emplacement'])?></td>
        <td><?=htmlspecialchars($c['franchise_id'])?></td>
        <td>
          <a href="/public/camion_edit.php?id=<?=htmlspecialchars($c['id'])?>">Modifier</a>
          <a href="/public/admin.php?delete_camion=<?=htmlspecialchars($c['id'])?>" onclick="return confirmDelete('camion', <?=htmlspecialchars($c['id'])?>)">Supprimer</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </section>

</body>
</html>
