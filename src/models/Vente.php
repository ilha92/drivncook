<?php

class Vente
{
    /* ==========================
       FRANCHISÉ
       ========================== */

    // Ajouter une vente (franchisé)
    public static function add($pdo, $franchise_id, $date, $montant)
    {
        $stmt = $pdo->prepare(
            "INSERT INTO ventes (franchise_id, date_vente, montant)
             VALUES (?, ?, ?)"
        );
        return $stmt->execute([$franchise_id, $date, $montant]);
    }

    // Récupérer les ventes d’un franchisé
    public static function getByFranchise($pdo, $franchise_id)
    {
        $stmt = $pdo->prepare(
            "SELECT * FROM ventes
             WHERE franchise_id = ?
             ORDER BY date_vente DESC"
        );
        $stmt->execute([$franchise_id]);
        return $stmt->fetchAll();
    }
    // Calcul du CA d’un franchisé
    public static function getCAByFranchise($pdo, $franchise_id)
    {
        $stmt = $pdo->prepare("
            SELECT SUM(montant) AS total
            FROM ventes
            WHERE franchise_id = ?
        ");
        $stmt->execute([$franchise_id]);
        return $stmt->fetchColumn() ?? 0;
    }

    /* ==========================
       ADMIN
       ========================== */

    // Toutes les ventes (admin)
    public static function getAll($pdo)
    {
        $sql = "
            SELECT ventes.*, franchises.nom AS franchise
            FROM ventes
            JOIN franchises ON ventes.franchise_id = franchises.id
            ORDER BY date_vente DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    // Calcul des 4% à reverser
    public static function getReversement($pdo, $franchise_id)
    {
        $ca = self::getCAByFranchise($pdo, $franchise_id);
        return $ca * 0.04;
    }
}
