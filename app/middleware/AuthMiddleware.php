<?php
require_once __DIR__ . '/../utils/JWTHelper.php';

class AuthMiddleware {
    
    public static function handle() {
        $headers = getallheaders();
        $token = null;

        // Buscar token en headers
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            // Formato: "Bearer {token}"
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        // Si no está en Authorization, buscar en query string o POST
        if (!$token && isset($_GET['token'])) {
            $token = $_GET['token'];
        }

        if (!$token) {
            http_response_code(401);
            echo json_encode(["error" => "Token no proporcionado"]);
            exit;
        }

        // Validar token
        $payload = JWTHelper::validate($token);

        if (!$payload) {
            http_response_code(401);
            echo json_encode(["error" => "Token inválido o expirado"]);
            exit;
        }

        // Guardar información del usuario en $_SERVER para uso en controladores
        $_SERVER['USER_ID'] = $payload['id'];
        $_SERVER['USER_USERNAME'] = $payload['username'];
        $_SERVER['USER_ROL'] = $payload['rol'];
        $_SERVER['USER_ID_ROL'] = $payload['idRol'];

        return true;
    }
}

