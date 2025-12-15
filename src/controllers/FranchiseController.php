<?php
// src/controllers/FranchiseController.php
require_once __DIR__ . '/../../config/database.php';
class FranchiseController {
    public static function index() {
        global $pdo;
        require_once __DIR__ . '/../models/Franchise.php';
        return Franchise::all();
    }
    public static function create($data) {
        require_once __DIR__ . '/../models/Franchise.php';
        return Franchise::create($data['user_id'] ?? null, $data['ville'] ?? null, $data['telephone'] ?? null, $data['date_entree'] ?? null);
    }
    public static function update($id, $data) {
        require_once __DIR__ . '/../models/Franchise.php';
        return Franchise::update($id, $data);
    }
    public static function delete($id) {
        require_once __DIR__ . '/../models/Franchise.php';
        return Franchise::delete($id);
    }
}
