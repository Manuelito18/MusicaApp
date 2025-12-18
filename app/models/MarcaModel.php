<?php
require_once __DIR__ . '/../config/database.php';

class MarcaModel {

    public static function getAll() {
        $db = Database::connect();
        $sql = "SELECT idmarca AS \"IdMarca\", nombre AS \"Nombre\", descripcion AS \"Descripcion\", paisorigen AS \"PaisOrigen\" FROM marca ORDER BY nombre";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "SELECT idmarca AS \"IdMarca\", nombre AS \"Nombre\", descripcion AS \"Descripcion\", paisorigen AS \"PaisOrigen\" FROM marca WHERE idmarca = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

