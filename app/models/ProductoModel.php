<?php
require_once __DIR__ . '/../config/database.php';

class ProductoModel {

    public static function getAll() {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.idproducto AS \"IdProducto\",
                p.nombre AS \"Nombre\",
                p.descripcion AS \"Descripcion\",
                p.precio AS \"Precio\",
                p.stock AS \"Stock\",
                p.imagenurl AS \"ImagenURL\",
                p.idcategoria AS \"IdCategoria\",
                p.idmarca AS \"IdMarca\",
                p.idestadoproducto AS \"IdEstadoProducto\",
                c.nombre AS \"Categoria\",
                m.nombre AS \"Marca\",
                ep.nombre AS \"Estado\",
                p.fechacreacion AS \"FechaCreacion\"
            FROM producto p
            INNER JOIN categoria c ON p.idcategoria = c.idcategoria
            INNER JOIN marca m ON p.idmarca = m.idmarca
            INNER JOIN estadoproducto ep ON p.idestadoproducto = ep.idestadoproducto
            ORDER BY p.fechacreacion DESC
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.idproducto AS \"IdProducto\",
                p.nombre AS \"Nombre\",
                p.descripcion AS \"Descripcion\",
                p.precio AS \"Precio\",
                p.stock AS \"Stock\",
                p.imagenurl AS \"ImagenURL\",
                p.idcategoria AS \"IdCategoria\",
                p.idmarca AS \"IdMarca\",
                p.idestadoproducto AS \"IdEstadoProducto\",
                p.fechacreacion AS \"FechaCreacion\"
            FROM producto p
            WHERE p.idproducto = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByCategory($idCategoria) {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.idproducto AS \"IdProducto\",
                p.nombre AS \"Nombre\",
                p.descripcion AS \"Descripcion\",
                p.precio AS \"Precio\",
                p.stock AS \"Stock\",
                p.imagenurl AS \"ImagenURL\",
                p.idcategoria AS \"IdCategoria\",
                p.idmarca AS \"IdMarca\",
                p.idestadoproducto AS \"IdEstadoProducto\",
                c.nombre AS \"Categoria\",
                m.nombre AS \"Marca\",
                ep.nombre AS \"Estado\",
                p.fechacreacion AS \"FechaCreacion\"
            FROM producto p
            INNER JOIN categoria c ON p.idcategoria = c.idcategoria
            INNER JOIN marca m ON p.idmarca = m.idmarca
            INNER JOIN estadoproducto ep ON p.idestadoproducto = ep.idestadoproducto
            WHERE p.idcategoria = ?
            ORDER BY p.fechacreacion DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idCategoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();
        $sql = "
            INSERT INTO producto 
            (nombre, descripcion, precio, stock, imagenurl, 
             idcategoria, idmarca, idestadoproducto)
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
            UPDATE producto SET
                nombre = ?,
                descripcion = ?,
                precio = ?,
                stock = ?,
                imagenurl = ?,
                idcategoria = ?,
                idmarca = ?,
                idestadoproducto = ?
            WHERE idproducto = ?
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
            UPDATE producto SET
                stock = ?
            WHERE idproducto = ?
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$stock, $id]);
    }

    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM producto WHERE idproducto = ?");
        return $stmt->execute([$id]);
    }
}
