<?php
require_once __DIR__ . '/../config/database.php';

class CategoriaModel {

    public static function getAll() {
        $db = Database::connect();
        // Evitar duplicados por ejecuciones repetidas de seed: tomamos una fila por nombre (case-insensitive)
        $sql = "
            SELECT DISTINCT ON (LOWER(nombre))
                idcategoria AS \"IdCategoria\",
                nombre AS \"Nombre\",
                descripcion AS \"Descripcion\"
            FROM categoria
            ORDER BY LOWER(nombre), idcategoria ASC
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "SELECT idcategoria AS \"IdCategoria\", nombre AS \"Nombre\", descripcion AS \"Descripcion\" FROM categoria WHERE idcategoria = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

