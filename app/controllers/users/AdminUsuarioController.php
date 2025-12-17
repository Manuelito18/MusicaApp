<?php
require_once __DIR__ . '/../../models/UsuarioModel.php';
require_once __DIR__ . '/../../models/UserDataModel.php';

class AdminUsuarioController {

    public static function index() {
        $usuarios = UsuarioModel::getAll();
        
        http_response_code(200);
        echo json_encode($usuarios);
    }

    public static function show($id) {
        $usuario = UsuarioModel::getById($id);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }

        http_response_code(200);
        echo json_encode($usuario);
    }

    public static function assignRole($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['idRol']) || !is_numeric($data['idRol'])) {
            http_response_code(400);
            echo json_encode(["error" => "idRol es requerido y debe ser numérico"]);
            return;
        }

        $result = UsuarioModel::updateRole($id, $data['idRol']);

        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Rol asignado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al asignar el rol"]);
        }
    }

    public static function blockUser($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $blocked = isset($data['blocked']) ? (bool)$data['blocked'] : true;

        $result = UsuarioModel::blockUser($id, $blocked);

        if ($result) {
            http_response_code(200);
            echo json_encode([
                "message" => $blocked ? "Usuario bloqueado correctamente" : "Usuario desbloqueado correctamente"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "error" => "Error al bloquear/desbloquear usuario",
                "message" => "Asegúrate de que exista un rol 'Bloqueado' en la base de datos, o agrega un campo 'Activo' BOOLEAN a la tabla Usuario"
            ]);
        }
    }
}

