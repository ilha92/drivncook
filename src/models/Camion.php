<?php

class Camion
{
    // ADMIN

    public static function getAll($pdo)
    {
        $sql = "SELECT camions.*, franchises.nom 
                FROM camions
                LEFT JOIN franchises ON camions.franchise_id = franchises.id";
        return $pdo->query($sql)->fetchAll();
    }

    public static function create($pdo, $immatriculation, $modele, $franchise_id)
    {
        $sql = "INSERT INTO camions (immatriculation, modele, franchise_id)
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$immatriculation, $modele, $franchise_id]);
    }

    public static function delete($pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM camions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function reparerCamion($pdo, $camion_id)
    {
        // Marquer toutes les pannes non réparées comme réparées
        $stmt = $pdo->prepare("
            UPDATE pannes SET reparée = 1 
            WHERE camion_id = ? AND reparée = 0
        ");
        $stmt->execute([$camion_id]);

        // Remettre le camion actif
        $stmt = $pdo->prepare("UPDATE camions SET statut = 'actif' WHERE id = ?");
        return $stmt->execute([$camion_id]);
    }

    /* =========================
       FRANCHISÉ
       ========================= */

    public static function getByFranchise($pdo, $franchise_id)
    {
        $stmt = $pdo->prepare("SELECT * FROM camions WHERE franchise_id = ?");
        $stmt->execute([$franchise_id]);
        return $stmt->fetchAll();
    }

    /* =========================
       PANNES (COMMUN)
       ========================= */

public static function addPanne($pdo, $camion_id, $date, $type, $description)
{
    //  Enregistrer la panne
    $sql = "INSERT INTO pannes (camion_id, date_panne, type_panne, description)
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$camion_id, $date, $type, $description]);

    //  Mettre le camion en panne
    $stmt = $pdo->prepare("
        UPDATE camions 
        SET statut = 'panne' 
        WHERE id = ?
    ");
    $stmt->execute([$camion_id]);
}

    public static function getPannes($pdo, $camion_id)
    {
        $stmt = $pdo->prepare("
            SELECT * FROM pannes 
            WHERE camion_id = ? 
            ORDER BY date_panne DESC
        ");
        $stmt->execute([$camion_id]);
        return $stmt->fetchAll();
    }
}
