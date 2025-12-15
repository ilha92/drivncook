<?php
// src/models/Franchise.php
require_once __DIR__ . '/../../config/database.php';
class Franchise {
    public static function all() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM franchises');
        return $stmt->fetchAll();
    }
    public static function create($userId, $ville = null, $telephone = null, $dateEntree = null) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO franchises (user_id, ville, telephone, date_entree) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $ville, $telephone, $dateEntree]);
        return $pdo->lastInsertId();
    }
    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM franchises WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE franchises SET ville = ?, telephone = ?, date_entree = ?, chiffre_affaires = ? WHERE id = ?');
        return $stmt->execute([
            $data['ville'] ?? null,
            $data['telephone'] ?? null,
            $data['date_entree'] ?? null,
            $data['chiffre_affaires'] ?? 0,
            $id
        ]);
    }
    public static function delete($id) {
        global $pdo;
        // supprimer les camions liés d'abord
        $delC = $pdo->prepare('DELETE FROM camions WHERE franchise_id = ?');
        $delC->execute([$id]);
        $stmt = $pdo->prepare('DELETE FROM franchises WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
