<?php

class Commande
{
    // Récupérer les produits
    public static function getProduits($pdo)
    {
        return $pdo->query("SELECT * FROM produits")->fetchAll();
    }

    // Passer une commande (avec contrôle du stock)
    public static function create($pdo, $franchise_id, $produit_id, $quantite)
    {
        // 1 Vérifier le stock disponible
        $stmt = $pdo->prepare("SELECT stock FROM produits WHERE id = ?");
        $stmt->execute([$produit_id]);
        $produit = $stmt->fetch();

        if ($quantite > $produit["stock"]) {
            return "Stock insuffisant";
        }

        // 2 Enregistrer la commande
        $stmt = $pdo->prepare("
            INSERT INTO commandes (franchise_id, produit_id, quantite)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$franchise_id, $produit_id, $quantite]);

        // 3. Mettre à jour le stock
        $stmt = $pdo->prepare("
            UPDATE produits
            SET stock = stock - ?
            WHERE id = ?
        ");
        $stmt->execute([$quantite, $produit_id]);

        return true;
    }

    // Historique des commandes
    public static function getByFranchise($pdo, $franchise_id)
    {
        $stmt = $pdo->prepare("
            SELECT commandes.*, produits.nom AS produit
            FROM commandes
            JOIN produits ON commandes.produit_id = produits.id
            WHERE commandes.franchise_id = ?
            ORDER BY commandes.date_commande DESC
        ");
        $stmt->execute([$franchise_id]);
        return $stmt->fetchAll();
    }
}
