<?php

class Franchise {

    public static function getById($pdo, $id) {
        $sql = "SELECT * FROM franchises WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function updateProfil($pdo, $ville, $telephone, $id) {
        $sql = "UPDATE franchises SET ville = ?, telephone = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$ville, $telephone, $id]);
    }

    // Récupérer tous les franchisés (admin)
    public static function getAll($pdo) {
        return $pdo->query("SELECT * FROM franchises")->fetchAll();
    }
}
