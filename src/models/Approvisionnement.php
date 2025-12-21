<?php

class Approvisionnement
{
    public static function getAllCommandes($pdo)
    {
        $sql = "
            SELECT commandes.*, franchises.nom AS franchise, produits.nom AS produit
            FROM commandes
            JOIN franchises ON commandes.franchise_id = franchises.id
            JOIN produits ON commandes.produit_id = produits.id
            ORDER BY commandes.date_commande DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    public static function validerCommande($pdo, $id)
    {
        $stmt = $pdo->prepare(
            "UPDATE commandes SET statut = 'validée' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
