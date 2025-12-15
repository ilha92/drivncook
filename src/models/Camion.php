<?php
// src/models/Camion.php
require_once __DIR__ . '/../../config/database.php';
class Camion {
    public static function all() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM camions');
        return $stmt->fetchAll();
    }
    public static function create($numero, $etat = 'actif', $emplacement = null, $franchise_id = null) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO camions (numero_camion, etat, emplacement, franchise_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$numero, $etat, $emplacement, $franchise_id]);
        return $pdo->lastInsertId();
    }
    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM camions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE camions SET numero_camion = ?, etat = ?, emplacement = ?, franchise_id = ? WHERE id = ?');
        return $stmt->execute([
            $data['numero_camion'] ?? null,
            $data['etat'] ?? 'actif',
            $data['emplacement'] ?? null,
            $data['franchise_id'] ?? null,
            $id
        ]);
    }
    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM camions WHERE id = ?');
        return $stmt->execute([$id]);
    }
    public static function allByFranchise($franchiseId) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM camions WHERE franchise_id = ?');
        $stmt->execute([$franchiseId]);
        return $stmt->fetchAll();
    }
}
