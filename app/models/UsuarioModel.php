<?php
require_once __DIR__ . '/../config/database.php';

class UsuarioModel {

    public static function getById($id) {
        $db = Database::connect();
        $sql = "
            SELECT 
                u.idusuario AS \"IdUsuario\",
                u.username AS \"Username\",
                u.idrol AS \"IdRol\",
                u.iduserdata AS \"IdUserData\",
                u.fecharegistro AS \"FechaRegistro\",
                r.nombre AS Rol,
                ud.nombres AS \"Nombres\",
                ud.apellidos AS \"Apellidos\",
                ud.email AS \"Email\",
                ud.telefono AS \"Telefono\",
                ud.numerodocumento AS \"NumeroDocumento\",
                ud.idtipodocumento AS \"IdTipoDocumento\"
            FROM usuario u
            LEFT JOIN rol r ON u.idrol = r.idrol
            LEFT JOIN userdata ud ON u.iduserdata = ud.iduserdata
            WHERE u.idusuario = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByUsername($username) {
        $db = Database::connect();
        $sql = "
            SELECT 
                u.idusuario AS \"IdUsuario\",
                u.username AS \"Username\",
                u.passwordhash AS \"PasswordHash\",
                u.idrol AS \"IdRol\",
                u.iduserdata AS \"IdUserData\",
                u.fecharegistro AS \"FechaRegistro\",
                r.nombre AS Rol
            FROM usuario u
            LEFT JOIN rol r ON u.idrol = r.idrol
            WHERE u.username = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $db = Database::connect();
        $sql = "
            SELECT 
                u.idusuario AS \"IdUsuario\",
                u.username AS \"Username\",
                u.idrol AS \"IdRol\",
                u.iduserdata AS \"IdUserData\",
                u.fecharegistro AS \"FechaRegistro\",
                r.nombre AS Rol,
                ud.nombres AS \"Nombres\",
                ud.apellidos AS \"Apellidos\",
                ud.email AS \"Email\",
                ud.telefono AS \"Telefono\"
            FROM usuario u
            LEFT JOIN rol r ON u.idrol = r.idrol
            LEFT JOIN userdata ud ON u.iduserdata = ud.iduserdata
            ORDER BY u.fecharegistro DESC
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();
        $sql = "
            INSERT INTO usuario 
            (username, passwordhash, idrol, iduserdata)
            VALUES (?, ?, ?, ?)
            RETURNING idusuario AS \"IdUsuario\"
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $data['username'],
            $data['passwordHash'],
            $data['idRol'],
            $data['idUserData'] ?? null
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['IdUsuario'];
    }

    public static function updateRole($id, $idRol) {
        $db = Database::connect();
        $sql = "
            UPDATE usuario SET
                idrol = ?
            WHERE idusuario = ?
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$idRol, $id]);
    }

    public static function blockUser($id, $blocked = true) {
        $db = Database::connect();
        
        // Opción 1: Si existe un rol "Bloqueado" (ID 5 por ejemplo, ajustar según tu BD)
        // Buscar si existe rol "Bloqueado"
        $sqlCheck = "SELECT idrol AS \"IdRol\" FROM rol WHERE nombre = 'Bloqueado'";
        $stmtCheck = $db->query($sqlCheck);
        $rolBloqueado = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($rolBloqueado && $blocked) {
            // Asignar rol Bloqueado
            return self::updateRole($id, $rolBloqueado['IdRol']);
        } else if (!$blocked) {
            // Desbloquear: asignar rol Cliente (ID 3 según data.sql)
            return self::updateRole($id, 3);
        }
        
        // Si no existe rol Bloqueado, retornar false
        // Nota: Para implementación completa, agregar campo "Activo" BOOLEAN a tabla Usuario
        return false;
    }

    public static function updateUserData($id, $idUserData) {
        $db = Database::connect();
        $sql = "
            UPDATE usuario SET
                iduserdata = ?
            WHERE idusuario = ?
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$idUserData, $id]);
    }
}

