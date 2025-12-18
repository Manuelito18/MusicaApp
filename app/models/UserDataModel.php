<?php
require_once __DIR__ . '/../config/database.php';

class UserDataModel {

    public static function getById($id) {
        $db = Database::connect();
        $sql = "
            SELECT 
                ud.iduserdata AS \"IdUserData\",
                ud.nombres AS \"Nombres\",
                ud.apellidos AS \"Apellidos\",
                ud.idtipodocumento AS \"IdTipoDocumento\",
                ud.numerodocumento AS \"NumeroDocumento\",
                ud.email AS \"Email\",
                ud.telefono AS \"Telefono\",
                td.nombre AS \"TipoDocumento\"
            FROM userdata ud
            LEFT JOIN tipodocumento td ON ud.idtipodocumento = td.idtipodocumento
            WHERE ud.iduserdata = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();
        $sql = "
            INSERT INTO userdata 
            (nombres, apellidos, idtipodocumento, numerodocumento, email, telefono)
            VALUES (?, ?, ?, ?, ?, ?)
            RETURNING iduserdata AS \"IdUserData\"
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $data['nombres'],
            $data['apellidos'],
            $data['idTipoDocumento'],
            $data['numeroDocumento'],
            $data['email'],
            $data['telefono'] ?? null
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['IdUserData'];
    }

    public static function update($id, $data) {
        $db = Database::connect();
        $sql = "
            UPDATE userdata SET
                nombres = ?,
                apellidos = ?,
                idtipodocumento = ?,
                numerodocumento = ?,
                email = ?,
                telefono = ?
            WHERE iduserdata = ?
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nombres'],
            $data['apellidos'],
            $data['idTipoDocumento'],
            $data['numeroDocumento'],
            $data['email'],
            $data['telefono'] ?? null,
            $id
        ]);
    }

    public static function getByEmail($email) {
        $db = Database::connect();
        $sql = "
            SELECT * FROM userdata
            WHERE email = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM userdata WHERE iduserdata = ?");
        return $stmt->execute([$id]);
    }
}

