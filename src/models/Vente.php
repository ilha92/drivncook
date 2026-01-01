<?php

class Vente
{
    /* ==========================
       FRANCHISÉ
       ========================== */

    // Ajouter une vente (franchisé)
    public static function add($pdo, $franchise_id, $date, $nom, $montant)
    {
        $stmt = $pdo->prepare(
            "INSERT INTO ventes (franchise_id, date_vente, nom, montant)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$franchise_id, $date, $nom, $montant]);
    }

    // Récupérer les ventes d’un franchisé
    public static function getByFranchise($pdo, $franchise_id)
    {
        $stmt = $pdo->prepare(
            "SELECT *
             FROM ventes
             WHERE franchise_id = ?
             ORDER BY date_vente DESC"
        );
        $stmt->execute([$franchise_id]);
        return $stmt->fetchAll();
    }

    // Calcul du chiffre d'affaires d’un franchisé
    public static function getCAByFranchise($pdo, $franchise_id)
    {
        $stmt = $pdo->prepare(
            "SELECT SUM(montant)
             FROM ventes
             WHERE franchise_id = ?"
        );
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
            ORDER BY ventes.date_vente DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    // CA global (tous franchisés)
    public static function getCATotal($pdo)
    {
        $stmt = $pdo->query(
            "SELECT SUM(montant) FROM ventes"
        );
        return $stmt->fetchColumn() ?? 0;
    }

    // Calcul des 4% à reverser pour un franchisé
    public static function getReversement($pdo, $franchise_id)
    {
        $ca = self::getCAByFranchise($pdo, $franchise_id);
        return round($ca * 0.04, 2);
    }
}
