<?php

class Franchise
{
    // methode communes aux franchises et admin
    public static function getById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM franchises WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getAll($pdo)
    {
        return $pdo->query("SELECT * FROM franchises")->fetchAll();
    }
    //Methodes pour l'admin
    // Création par l'admin
    public static function create($pdo, $nom, $email, $ville, $telephone, $date_entree)
    {
        $sql = "INSERT INTO franchises (nom, email, mot_de_passe, ville, telephone, date_entree, droit_entree)
                VALUES (?, ?, '', ?, ?, ?, 0)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$nom, $email, $ville, $telephone, $date_entree]);
    }

    // Modification complète par l'admin
    public static function updateByAdmin($pdo, $nom, $email, $droit_entree, $ville, $telephone, $id)
    {
        $sql = "UPDATE franchises
                SET nom = ?, email = ?, droit_entree = ?, ville = ?, telephone = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$nom, $email, $droit_entree, $ville, $telephone, $id]);
    }

    // Suppression par l'admin
    public static function delete($pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM franchises WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Validation du droit d'entrée
    public static function validerDroitEntree($pdo, $id)
    {
        $stmt = $pdo->prepare("UPDATE franchises SET droit_entree = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    //Methodes pour le franchisé
    // Modifier son profil (email, ville + téléphone + mot de passe)
    public static function updateProfil($pdo, $email, $nom, $ville, $telephone, $password, $id)
    {
        $sql = "UPDATE franchises SET email = ?, nom = ?, ville = ?, telephone = ?, mot_de_passe = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$email, $nom, $ville, $telephone, password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    // Modifier son email
    public static function updateEmail($pdo, $email, $id)
    {
        $stmt = $pdo->prepare("UPDATE franchises SET email = ? WHERE id = ?");
        return $stmt->execute([$email, $id]);
    }

    // Modifier son mot de passe
    public static function updatePassword($pdo, $password, $id)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE franchises SET mot_de_passe = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    // Récupérer les ventes d'une franchise
    public static function getVentes($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM ventes WHERE franchise_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
}
