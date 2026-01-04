<?php
class Produit
{
      public static function getById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tous les produits
    public static function getAll($pdo)
    {
        $sql = "
            SELECT produits.*, entrepots.nom AS entrepot
            FROM produits
            JOIN entrepots ON produits.entrepot_id = entrepots.id
        ";
        return $pdo->query($sql)->fetchAll();
    }

    // vois les entrepôts
    public static function getEntrepots($pdo) {
        $stmt = $pdo->query("SELECT id, nom FROM entrepots WHERE actif = 1 ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // je crée un produit
    public static function create($pdo, $nom, $prix, $stock, $entrepot_id)
    {
        $stmt = $pdo->prepare(
            "INSERT INTO produits (nom, prix, stock, entrepot_id)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$nom, $prix, $stock, $entrepot_id]);
    }

    // Modification complète par l'admin
    public static function updateByAdmin($pdo, $nom, $prix, $stock, $entrepot_id, $id)
    {
        $sql = "UPDATE produits
                SET nom = ?, prix = ?, stock = ?, entrepot_id = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$nom, $prix, $stock, $entrepot_id, $id]);
    }

    // Suppression d'un produit par l'admin
    public static function delete($pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
