<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/UsuarioModel.php';
require_once __DIR__ . '/UserDataModel.php';

class TrabajadorModel {

    // role_id = 2 => Trabajador (según data.sql)
    private const ROLE_TRABAJADOR = 2;

    public static function getAll() {
        $db = Database::connect();
        $sql = "
            SELECT
                u.idusuario AS \"IdUsuario\",
                u.username AS \"Username\",
                u.idrol AS \"IdRol\",
                r.nombre AS \"Rol\",
                u.fecharegistro AS \"FechaRegistro\",
                ud.iduserdata AS \"IdUserData\",
                ud.nombres AS \"Nombres\",
                ud.apellidos AS \"Apellidos\",
                ud.email AS \"Email\",
                ud.telefono AS \"Telefono\"
            FROM usuario u
            INNER JOIN rol r ON u.idrol = r.idrol
            LEFT JOIN userdata ud ON u.iduserdata = ud.iduserdata
            WHERE u.idrol = ?
            ORDER BY u.fecharegistro DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([self::ROLE_TRABAJADOR]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countAll() {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT COUNT(*) AS \"TotalTrabajadores\" FROM usuario WHERE idrol = ?");
        $stmt->execute([self::ROLE_TRABAJADOR]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        // Crea userdata y luego usuario con rol trabajador
        $idUserData = null;

        if (isset($data['nombres']) && isset($data['apellidos']) && isset($data['email'])) {
            $userData = [
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'idTipoDocumento' => $data['idTipoDocumento'] ?? 1,
                'numeroDocumento' => $data['numeroDocumento'] ?? '',
                'email' => $data['email'],
                'telefono' => $data['telefono'] ?? null
            ];
            $idUserData = UserDataModel::create($userData);
        }

        $usuarioData = [
            'username' => $data['username'],
            'passwordHash' => $data['passwordHash'],
            'idRol' => self::ROLE_TRABAJADOR,
            'idUserData' => $idUserData
        ];

        return UsuarioModel::create($usuarioData);
    }

    public static function update($idUsuario, $data) {
        $db = Database::connect();

        // Actualizar username si viene
        if (isset($data['username']) && $data['username'] !== '') {
            $stmt = $db->prepare("UPDATE usuario SET username = ? WHERE idusuario = ?");
            $stmt->execute([$data['username'], $idUsuario]);
        }

        // Actualizar password si viene
        if (isset($data['passwordHash']) && $data['passwordHash'] !== '') {
            $stmt = $db->prepare("UPDATE usuario SET passwordhash = ? WHERE idusuario = ?");
            $stmt->execute([$data['passwordHash'], $idUsuario]);
        }

        // Mantener rol trabajador por RBAC
        $stmt = $db->prepare("UPDATE usuario SET idrol = ? WHERE idusuario = ?");
        $stmt->execute([self::ROLE_TRABAJADOR, $idUsuario]);

        // Actualizar userdata si existe
        $stmtGet = $db->prepare("SELECT iduserdata FROM usuario WHERE idusuario = ?");
        $stmtGet->execute([$idUsuario]);
        $row = $stmtGet->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['iduserdata']) && isset($data['userData'])) {
            UserDataModel::update((int)$row['iduserdata'], $data['userData']);
        }

        return true;
    }

    public static function delete($idUsuario) {
        // Borrar usuario y si tiene userdata, borrarlo también (mejor esfuerzo)
        $res = UsuarioModel::delete($idUsuario);
        if (!$res['success']) return false;

        if (!empty($res['idUserData'])) {
            // Solo borramos userdata si ya no está referenciada por ningún usuario
            $db = Database::connect();
            $stmt = $db->prepare("SELECT COUNT(*) AS c FROM usuario WHERE iduserdata = ?");
            $stmt->execute([$res['idUserData']]);
            $count = (int)($stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);
            if ($count === 0) {
                UserDataModel::delete((int)$res['idUserData']);
            }
        }

        return true;
    }
}


