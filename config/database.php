<?php
$host = "localhost";
$dbname = "drivncook";
$username = "root";
$password = ""; 
$port = "3306"; 

try {
    // Connexion à MySQL avec PDO
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    // Affiche les erreurs SQL (utile pour débutant)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Message si la connexion échoue
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}