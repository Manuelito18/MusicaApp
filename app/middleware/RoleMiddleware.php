<?php

class RoleMiddleware {
    
    public static function handle($allowedRoles) {
        // Primero verificar que AuthMiddleware haya sido ejecutado
        if (!isset($_SERVER['USER_ROL'])) {
            http_response_code(401);
            echo json_encode(["error" => "No autorizado. Token requerido"]);
            exit;
        }

        $userRole = $_SERVER['USER_ROL'];

        // Si $allowedRoles es un string, convertirlo a array
        if (is_string($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        // Verificar si el rol del usuario está en los roles permitidos
        if (!in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            echo json_encode([
                "error" => "Acceso denegado",
                "message" => "No tienes permisos para acceder a este recurso",
                "required_roles" => $allowedRoles,
                "your_role" => $userRole
            ]);
            exit;
        }

        return true;
    }
}

