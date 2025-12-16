<?php

class Camion {

    // Camions d’un franchisé
    public static function getByFranchise($pdo, $franchise_id) {
        $sql = "SELECT * FROM camions WHERE franchise_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$franchise_id]);
        return $stmt->fetchAll();
    }

    // Tous les camions (admin)
    public static function getAll($pdo) {
        $sql = "
        SELECT camions.*, franchises.nom
        FROM camions
        JOIN franchises ON camions.franchise_id = franchises.id
        ";
        return $pdo->query($sql)->fetchAll();
    }

    // Changer le statut du camion
    public static function updateStatut($pdo, $statut, $id) {
        $sql = "UPDATE camions SET statut = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$statut, $id]);
    }
}
