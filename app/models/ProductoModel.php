<?php
require_once __DIR__ . '/../config/database.php';

class ProductoModel {

    public static function getAll() {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.\"IdProducto\",
                p.\"Nombre\",
                p.\"Descripcion\",
                p.\"Precio\",
                p.\"Stock\",
                p.\"ImagenURL\",
                c.\"Nombre\" AS Categoria,
                m.\"Nombre\" AS Marca,
                ep.\"Nombre\" AS Estado,
                p.\"FechaCreacion\"
            FROM \"Producto\" p
            INNER JOIN \"Categoria\" c ON p.\"IdCategoria\" = c.\"IdCategoria\"
            INNER JOIN \"Marca\" m ON p.\"IdMarca\" = m.\"IdMarca\"
            INNER JOIN \"EstadoProducto\" ep ON p.\"IdEstadoProducto\" = ep.\"IdEstadoProducto\"
            ORDER BY p.\"FechaCreacion\" DESC
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "
            SELECT * FROM \"Producto\"
            WHERE \"IdProducto\" = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByCategory($idCategoria) {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.\"IdProducto\",
                p.\"Nombre\",
                p.\"Descripcion\",
                p.\"Precio\",
                p.\"Stock\",
                p.\"ImagenURL\",
                c.\"Nombre\" AS Categoria,
                m.\"Nombre\" AS Marca,
                ep.\"Nombre\" AS Estado,
                p.\"FechaCreacion\"
            FROM \"Producto\" p
            INNER JOIN \"Categoria\" c ON p.\"IdCategoria\" = c.\"IdCategoria\"
            INNER JOIN \"Marca\" m ON p.\"IdMarca\" = m.\"IdMarca\"
            INNER JOIN \"EstadoProducto\" ep ON p.\"IdEstadoProducto\" = ep.\"IdEstadoProducto\"
            WHERE p.\"IdCategoria\" = ?
            ORDER BY p.\"FechaCreacion\" DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idCategoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();
        $sql = "
            INSERT INTO \"Producto\" 
            (\"Nombre\", \"Descripcion\", \"Precio\", \"Stock\", \"ImagenURL\", 
             \"IdCategoria\", \"IdMarca\", \"IdEstadoProducto\")
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['imagen'],
            $data['idCategoria'],
            $data['idMarca'],
            $data['idEstadoProducto']
        ]);
    }

    public static function update($id, $data) {
        $db = Database::connect();
        $sql = "
            UPDATE \"Producto\" SET
                \"Nombre\" = ?,
                \"Descripcion\" = ?,
                \"Precio\" = ?,
                \"Stock\" = ?,
                \"ImagenURL\" = ?,
                \"IdCategoria\" = ?,
                \"IdMarca\" = ?,
                \"IdEstadoProducto\" = ?
            WHERE \"IdProducto\" = ?
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['imagen'],
            $data['idCategoria'],
            $data['idMarca'],
            $data['idEstadoProducto'],
            $id
        ]);
    }

    public static function updateStock($id, $stock) {
        $db = Database::connect();
        $sql = "
            UPDATE \"Producto\" SET
                \"Stock\" = ?
            WHERE \"IdProducto\" = ?
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$stock, $id]);
    }

    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM \"Producto\" WHERE \"IdProducto\" = ?");
        return $stmt->execute([$id]);
    }
}
