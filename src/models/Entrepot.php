<?php
class Entrepot {
    
    public static function getById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM entrepots WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM entrepots ORDER BY id DESC");
        // recupère tous les entrepôts sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getEntrepots($pdo) {
        $stmt = $pdo->query("SELECT id, nom FROM entrepots WHERE actif = 1 ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($pdo, $nom, $ville, $prix) {
        $stmt = $pdo->prepare("INSERT INTO entrepots (nom, ville, prix, actif) VALUES (?, ?, ?, 1)");
        return $stmt->execute([$nom, $ville, $prix]);
    }

    public static function update($pdo, $id, $nom, $ville, $prix, $actif = 1, $stock = 1): bool 
    {
        $stmt = $pdo->prepare("UPDATE entrepots SET nom=?, ville=?, prix=?, actif=?, stock=? WHERE id=?");
        $result = $stmt->execute([$nom, $ville, $prix, $actif, $stock, $id]);
        return $result && $stmt->rowCount() > 0;
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM entrepots WHERE id = ?");
        return $stmt->execute([$id]);
    }
}