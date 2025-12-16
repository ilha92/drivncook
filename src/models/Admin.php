<?php

class Admin {

    // Récupérer toutes les ventes (admin)
    public static function getAllVentes($pdo) {
        $sql = "
        SELECT ventes.date_vente, ventes.montant, franchises.nom
        FROM ventes
        JOIN franchises ON ventes.franchise_id = franchises.id
        ORDER BY ventes.date_vente DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    // Récupérer tous les achats (admin)
    public static function getAllAchats($pdo) {
        $sql = "
        SELECT achats.date_achat, achats.montant_total, franchises.nom, entrepots.nom AS entrepot
        FROM achats
        JOIN franchises ON achats.franchise_id = franchises.id
        LEFT JOIN entrepots ON achats.entrepot_id = entrepots.id
        ORDER BY achats.date_achat DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    // Récupérer tous les franchisés
    public static function getAllFranchises($pdo) {
        return $pdo->query("SELECT * FROM franchises")->fetchAll();
    }
}
