<?php
require_once __DIR__ . '/../config/database.php';

// Venta ~= Pedido (ventas registradas)
class VentaModel {

    public static function getAll() {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.idpedido AS \"IdVenta\",
                p.idpedido AS \"IdPedido\",
                p.fecha AS \"Fecha\",
                p.total AS \"Total\",
                u.username AS \"Username\",
                ud.nombres AS \"Nombres\",
                ud.apellidos AS \"Apellidos\",
                ud.email AS \"Email\",
                ep.nombre AS \"EstadoPedido\",
                e.nombrecomercial AS \"Empresa\"
            FROM pedido p
            INNER JOIN usuario u ON p.idusuario = u.idusuario
            LEFT JOIN userdata ud ON u.iduserdata = ud.iduserdata
            INNER JOIN estadopedido ep ON p.idestadopedido = ep.idestadopedido
            INNER JOIN datosempresa e ON p.idempresa = e.idempresa
            ORDER BY p.fecha DESC
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.idpedido AS \"IdVenta\",
                p.idpedido AS \"IdPedido\",
                p.fecha AS \"Fecha\",
                p.total AS \"Total\",
                u.username AS \"Username\",
                ud.nombres AS \"Nombres\",
                ud.apellidos AS \"Apellidos\",
                ud.email AS \"Email\",
                ud.telefono AS \"Telefono\",
                ep.nombre AS \"EstadoPedido\",
                e.nombrecomercial AS \"Empresa\"
            FROM pedido p
            INNER JOIN usuario u ON p.idusuario = u.idusuario
            LEFT JOIN userdata ud ON u.iduserdata = ud.iduserdata
            INNER JOIN estadopedido ep ON p.idestadopedido = ep.idestadopedido
            INNER JOIN datosempresa e ON p.idempresa = e.idempresa
            WHERE p.idpedido = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getDetallesByVentaId($idVenta) {
        $db = Database::connect();
        $sql = "
            SELECT 
                dp.idproducto AS \"IdProducto\",
                dp.cantidad AS \"Cantidad\",
                dp.preciounitario AS \"PrecioUnitario\",
                dp.subtotal AS \"Subtotal\",
                pr.nombre AS \"ProductoNombre\",
                pr.imagenurl AS \"ImagenURL\"
            FROM detallepedido dp
            INNER JOIN producto pr ON dp.idproducto = pr.idproducto
            WHERE dp.idpedido = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDashboardMetrics() {
        $db = Database::connect();
        $sql = "
            SELECT 
                COUNT(*) AS \"TotalPedidos\",
                COALESCE(SUM(total), 0) AS \"TotalVentas\"
            FROM pedido
            WHERE idestadopedido IN (2, 3, 4)
        ";
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


