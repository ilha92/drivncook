<?php
// src/models/User.php
require_once __DIR__ . '/../../config/database.php';
class User {
    public static function findByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    public static function findById($id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function create($nom, $email, $motDePasse, $role = 'franchise') {
        global $pdo;
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nom, $email, $hash, $role]);
        return $pdo->lastInsertId();
    }
    public static function all() {
        global $pdo;
        $stmt = $pdo->query('SELECT id, nom, email, role, date_creation FROM users');
        return $stmt->fetchAll();
    }
    public static function delete($id) {
        global $pdo;
        // Delete related franchises and camions first to avoid FK constraint errors
        $stmt = $pdo->prepare('SELECT id FROM franchises WHERE user_id = ?');
        $stmt->execute([$id]);
        $franchises = $stmt->fetchAll();
        $delF = $pdo->prepare('DELETE FROM camions WHERE franchise_id = ?');
        $delFr = $pdo->prepare('DELETE FROM franchises WHERE id = ?');
        foreach ($franchises as $f) {
            $delF->execute([$f['id']]);
            $delFr->execute([$f['id']]);
        }
        $delUser = $pdo->prepare('DELETE FROM users WHERE id = ?');
        return $delUser->execute([$id]);
    }
}
