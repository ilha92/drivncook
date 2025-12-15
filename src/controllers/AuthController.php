<?php
// src/controllers/AuthController.php
require_once __DIR__ . '/../../config/database.php';
class AuthController {
    public static function login($email, $password) {
        require_once __DIR__ . '/../models/User.php';
        $user = User::findByEmail($email);
        if (!$user) {
            return ['success' => false, 'message' => 'Utilisateur introuvable'];
        }
        if (!password_verify($password, $user['mot_de_passe'])) {
            return ['success' => false, 'message' => 'Mot de passe incorrect'];
        }
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        return ['success' => true, 'message' => 'Authentification réussie', 'user' => $user];
    }
    public static function register($data) {
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Franchise.php';
        $nom = $data['name'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        if (!$nom || !$email || !$password) {
            return ['success' => false, 'message' => 'Données manquantes'];
        }
        // Vérifier si l'email existe
        if (User::findByEmail($email)) {
            return ['success' => false, 'message' => 'Email déjà utilisé'];
        }
        $userId = User::create($nom, $email, $password, 'franchise');
        if (!$userId) {
            return ['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur'];
        }
        Franchise::create($userId);
        return ['success' => true, 'message' => 'Compte créé', 'user_id' => $userId];
    }
    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
