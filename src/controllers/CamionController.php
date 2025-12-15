<?php
// src/controllers/CamionController.php
require_once __DIR__ . '/../../config/database.php';
class CamionController {
    public static function index() {
        global $pdo;
        require_once __DIR__ . '/../models/Camion.php';
        return Camion::all();
    }
    public static function assignToFranchise($camionId, $franchiseId) {
        require_once __DIR__ . '/../models/Camion.php';
        return Camion::update($camionId, ['franchise_id' => $franchiseId]);
    }
    public static function create($data) {
        require_once __DIR__ . '/../models/Camion.php';
        return Camion::create($data['numero_camion'] ?? '', $data['etat'] ?? 'actif', $data['emplacement'] ?? null, $data['franchise_id'] ?? null);
    }
    public static function update($id, $data) {
        require_once __DIR__ . '/../models/Camion.php';
        return Camion::update($id, $data);
    }
    public static function delete($id) {
        require_once __DIR__ . '/../models/Camion.php';
        return Camion::delete($id);
    }
}
