<?php

class Commande
{
    // Produits disponibles
    public static function getProduits($pdo)
    {
        return $pdo->query("SELECT * FROM produits")->fetchAll();
    }

    // Passer une commande
    public static function create($pdo, $franchise_id, $produit_id, $quantite)
    {
        $stmt = $pdo->prepare(
            "INSERT INTO commandes (franchise_id, produit_id, quantite)
             VALUES (?, ?, ?)"
        );
        return $stmt->execute([$franchise_id, $produit_id, $quantite]);
    }

    // Historique des commandes du franchisé
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
