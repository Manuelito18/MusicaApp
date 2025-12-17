<?php
require_once __DIR__ . '/../config/database.php';

class MarcaModel {

    public static function getAll() {
        $db = Database::connect();
        $sql = "SELECT \"IdMarca\", \"Nombre\", \"Descripcion\", \"PaisOrigen\" FROM \"Marca\" ORDER BY \"Nombre\"";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "SELECT * FROM \"Marca\" WHERE \"IdMarca\" = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

