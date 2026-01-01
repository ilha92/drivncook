<?php

class Camion
{
    /* =========================
      COTER  ADMIN
       ========================= */

   public static function getAll($pdo)
{
    $sql = "
        SELECT 
            camions.*,
            franchises.nom AS franchise_nom,
            pannes.type_panne,
            pannes.description AS panne_description
        FROM camions
        LEFT JOIN franchises 
            ON camions.franchise_id = franchises.id
        LEFT JOIN pannes 
            ON pannes.camion_id = camions.id
            AND pannes.reparée = 0
        ORDER BY pannes.date_panne DESC
    ";

    return $pdo->query($sql)->fetchAll();
}


    // récupérer un camion par ID (admin)
    public static function getById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM camions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($pdo, $immatriculation, $modele, $statut, $franchise_id)
    {
        $stmt = $pdo->prepare(
            "INSERT INTO camions (immatriculation, modele, statut, franchise_id)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$immatriculation, $modele, $statut, $franchise_id]);
    }

    // modifier un camion (admin)
    public static function update($pdo, $immatriculation, $modele, $statut, $franchise_id, $id)
    {
        $stmt = $pdo->prepare(
            "UPDATE camions 
             SET immatriculation = ?, modele = ?, statut = ?, franchise_id = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $immatriculation,
            $modele,
            $statut,
            $franchise_id,
            $id
        ]);
    }

        public static function getModeles()
{
    return [
        "Renault Master",
        "Mercedes-Benz Sprinter",
        "Iveco Daily",
        "Mercedes-Benz",
        "MAN TGM",
        "Renault Trucks Gamme D",
        "Scania Série P",
        "Mercedes-Benz Atego",
        "Volvo FL",
        "Iveco Eurocargo"
    ];
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
            UPDATE pannes 
            SET reparée = 1 
            WHERE camion_id = ? AND reparée = 0
        ");
        $stmt->execute([$camion_id]);

        // Remettre le camion actif
        $stmt = $pdo->prepare("UPDATE camions SET statut = 'actif' WHERE id = ?");
        return $stmt->execute([$camion_id]);
    }


    /* =========================
        COTER FRANCHISÉ
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
        // Enregistrer la panne
        $sql = "INSERT INTO pannes (camion_id, date_panne, type_panne, description)
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$camion_id, $date, $type, $description]);

        // Mettre le camion en panne
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
