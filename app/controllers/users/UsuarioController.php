<?php
require_once __DIR__ . '/../../models/UsuarioModel.php';
require_once __DIR__ . '/../../models/UserDataModel.php';

class UsuarioController {

    public static function show() {
        // El ID del usuario viene del middleware AuthMiddleware
        $userId = $_SERVER['USER_ID'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(["error" => "No autorizado"]);
            return;
        }

        $usuario = UsuarioModel::getById($userId);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }

        http_response_code(200);
        echo json_encode($usuario);
    }

    public static function getRole() {
        $userId = $_SERVER['USER_ID'] ?? null;
        $userRol = $_SERVER['USER_ROL'] ?? null;

        if (!$userId || !$userRol) {
            http_response_code(401);
            echo json_encode(["error" => "No autorizado"]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "idUsuario" => $userId,
            "rol" => $userRol
        ]);
    }

    public static function updateUserData() {
        $userId = $_SERVER['USER_ID'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(["error" => "No autorizado"]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos"]);
            return;
        }

        // Obtener usuario para verificar si tiene UserData
        $usuario = UsuarioModel::getById($userId);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }

        $idUserData = $usuario['IdUserData'];

        if (!$idUserData) {
            // Si no tiene UserData, crear uno nuevo
            $userData = [
                'nombres' => $data['nombres'] ?? '',
                'apellidos' => $data['apellidos'] ?? '',
                'idTipoDocumento' => $data['idTipoDocumento'] ?? 1,
                'numeroDocumento' => $data['numeroDocumento'] ?? '',
                'email' => $data['email'] ?? '',
                'telefono' => $data['telefono'] ?? null
            ];
            $idUserData = UserDataModel::create($userData);
            UsuarioModel::updateUserData($userId, $idUserData);
        } else {
            // Actualizar UserData existente
            $updateData = [
                'nombres' => $data['nombres'] ?? $usuario['Nombres'],
                'apellidos' => $data['apellidos'] ?? $usuario['Apellidos'],
                'idTipoDocumento' => $data['idTipoDocumento'] ?? $usuario['IdTipoDocumento'],
                'numeroDocumento' => $data['numeroDocumento'] ?? $usuario['NumeroDocumento'],
                'email' => $data['email'] ?? $usuario['Email'],
                'telefono' => $data['telefono'] ?? $usuario['Telefono']
            ];
            UserDataModel::update($idUserData, $updateData);
        }

        http_response_code(200);
        echo json_encode(["message" => "Datos actualizados correctamente"]);
    }
}

