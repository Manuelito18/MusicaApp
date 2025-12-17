<?php
require_once __DIR__ . '/../config/database.php';

class CategoriaModel {

    public static function getAll() {
        $db = Database::connect();
        $sql = "SELECT \"IdCategoria\", \"Nombre\", \"Descripcion\" FROM \"Categoria\" ORDER BY \"Nombre\"";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "SELECT * FROM \"Categoria\" WHERE \"IdCategoria\" = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

