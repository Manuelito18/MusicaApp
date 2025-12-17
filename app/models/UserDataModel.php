<?php
require_once __DIR__ . '/../config/database.php';

class UserDataModel {

    public static function getById($id) {
        $db = Database::connect();
        $sql = "
            SELECT 
                ud.\"IdUserData\",
                ud.\"Nombres\",
                ud.\"Apellidos\",
                ud.\"IdTipoDocumento\",
                ud.\"NumeroDocumento\",
                ud.\"Email\",
                ud.\"Telefono\",
                td.\"Nombre\" AS TipoDocumento
            FROM userdata ud
            LEFT JOIN tipodocumento td ON ud.\"IdTipoDocumento\" = td.\"IdTipoDocumento\"
            WHERE ud.\"IdUserData\" = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();
        $sql = "
            INSERT INTO userdata 
            (\"Nombres\", \"Apellidos\", \"IdTipoDocumento\", \"NumeroDocumento\", \"Email\", \"Telefono\")
            VALUES (?, ?, ?, ?, ?, ?)
            RETURNING \"IdUserData\"
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
                \"Nombres\" = ?,
                \"Apellidos\" = ?,
                \"IdTipoDocumento\" = ?,
                \"NumeroDocumento\" = ?,
                \"Email\" = ?,
                \"Telefono\" = ?
            WHERE \"IdUserData\" = ?
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
            WHERE \"Email\" = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

