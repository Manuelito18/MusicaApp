<?php
require_once __DIR__ . '/../config/database.php';

class PedidoModel {

    public static function getAll() {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.\"IdPedido\",
                p.\"Fecha\",
                p.\"Total\",
                u.\"Username\",
                ud.\"Nombres\",
                ud.\"Apellidos\",
                ud.\"Email\",
                ep.\"Nombre\" AS EstadoPedido,
                e.\"NombreComercial\" AS Empresa
            FROM \"Pedido\" p
            INNER JOIN \"Usuario\" u ON p.\"IdUsuario\" = u.\"IdUsuario\"
            LEFT JOIN \"UserData\" ud ON u.\"IdUserData\" = ud.\"IdUserData\"
            INNER JOIN \"EstadoPedido\" ep ON p.\"IdEstadoPedido\" = ep.\"IdEstadoPedido\"
            INNER JOIN \"DatosEmpresa\" e ON p.\"IdEmpresa\" = e.\"IdEmpresa\"
            ORDER BY p.\"Fecha\" DESC
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $sql = "
            SELECT 
                p.\"IdPedido\",
                p.\"Fecha\",
                p.\"Total\",
                u.\"Username\",
                ud.\"Nombres\",
                ud.\"Apellidos\",
                ud.\"Email\",
                ud.\"Telefono\",
                ep.\"Nombre\" AS EstadoPedido,
                e.\"NombreComercial\" AS Empresa
            FROM \"Pedido\" p
            INNER JOIN \"Usuario\" u ON p.\"IdUsuario\" = u.\"IdUsuario\"
            LEFT JOIN \"UserData\" ud ON u.\"IdUserData\" = ud.\"IdUserData\"
            INNER JOIN \"EstadoPedido\" ep ON p.\"IdEstadoPedido\" = ep.\"IdEstadoPedido\"
            INNER JOIN \"DatosEmpresa\" e ON p.\"IdEmpresa\" = e.\"IdEmpresa\"
            WHERE p.\"IdPedido\" = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getDetallesByPedidoId($idPedido) {
        $db = Database::connect();
        $sql = "
            SELECT 
                dp.\"IdProducto\",
                dp.\"Cantidad\",
                dp.\"PrecioUnitario\",
                dp.\"Subtotal\",
                pr.\"Nombre\" AS ProductoNombre,
                pr.\"ImagenURL\"
            FROM \"DetallePedido\" dp
            INNER JOIN \"Producto\" pr ON dp.\"IdProducto\" = pr.\"IdProducto\"
            WHERE dp.\"IdPedido\" = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idPedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTotalVentas() {
        $db = Database::connect();
        $sql = "
            SELECT 
                COUNT(*) AS TotalPedidos,
                COALESCE(SUM(\"Total\"), 0) AS TotalVentas
            FROM \"Pedido\"
            WHERE \"IdEstadoPedido\" IN (2, 3, 4) -- Pagado, Enviado, Entregado
        ";
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

