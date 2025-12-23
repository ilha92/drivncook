<?php

class Approvisionnement
{
    public static function getAllCommandes($pdo)
    {
        $sql = "
            SELECT commandes.*, 
                   franchises.nom AS franchise, 
                   produits.nom AS produit,
                   produits.stock,
                   produits.id AS produit_id
            FROM commandes
            JOIN franchises ON commandes.franchise_id = franchises.id
            JOIN produits ON commandes.produit_id = produits.id
            ORDER BY commandes.date_commande DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    public static function delete($pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function validerCommande($pdo, $commande_id, $produit_id, $quantite)
    {
        // 1️⃣ Diminuer le stock
        $stmt = $pdo->prepare(
            "UPDATE produits 
             SET stock = stock - ? 
             WHERE id = ? AND stock >= ?"
        );
        $stmt->execute([$quantite, $produit_id, $quantite]);

        if ($stmt->rowCount() === 0) {
            return false; // stock insuffisant
        }

        // 2️⃣ Mettre à jour le statut
        $stmt = $pdo->prepare(
            "UPDATE commandes SET statut = 'validée' WHERE id = ?"
        );
        return $stmt->execute([$commande_id]);
    }
}
