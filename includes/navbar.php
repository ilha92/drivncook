<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isConnected = isset($_SESSION['type']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="../index.php">
            <img src="../../images/livraison-rapide.png" width="30" height="30" alt=""> Driv'n Cook
        </a>
        <!-- Bouton mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- Menu gauche -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($isConnected): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/franchise/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/franchise/commandes.php">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/franchise/ventes.php">Ventes</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Actions droite -->
            <div class="d-flex gap-2 align-items-center">

                <!-- Bouton mode sombre -->
                <button id="toggleTheme" class="btn btn-outline-light btn-sm">
                    🌙
                </button>

                <?php if ($isConnected): ?>
                    <!-- Profil -->
                    <a href="/public/franchise/profil.php" class="btn btn-success btn-sm">
                        👤 Profil
                    </a>
                    <a href="/access/logout.php" class="btn btn-danger btn-sm">
                        Déconnexion
                    </a>
                <?php else: ?>
                    <a href="/access/login.php" class="btn btn-outline-light btn-sm">
                        Connexion
                    </a>
                    <a href="/access/register.php" class="btn btn-success btn-sm">
                        Inscription
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>
<script>
const toggleBtn = document.getElementById("toggleTheme");
const body = document.body;

// Charger le thème sauvegardé
if (localStorage.getItem("theme") === "dark") {
    body.classList.add("bg-dark", "text-light");
}

toggleBtn.addEventListener("click", () => {
    body.classList.toggle("bg-dark");
    body.classList.toggle("text-light");

    if (body.classList.contains("bg-dark")) {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }
});
</script>