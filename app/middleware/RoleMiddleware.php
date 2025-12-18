<?php

class RoleMiddleware {
    
    public static function handle($allowedRoles) {
        // Primero verificar que AuthMiddleware haya sido ejecutado
        if (!isset($_SERVER['USER_ROL']) && !isset($_SERVER['USER_ID_ROL'])) {
            http_response_code(401);
            echo json_encode(["error" => "No autorizado. Token requerido"]);
            exit;
        }

        $userRoleName = $_SERVER['USER_ROL'] ?? null;
        $userRoleId = isset($_SERVER['USER_ID_ROL']) ? (int)$_SERVER['USER_ID_ROL'] : null;

        // Si $allowedRoles es un string, convertirlo a array
        if (is_string($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        // Verificar si el rol del usuario está en los roles permitidos (por nombre o por ID)
        $allowed = false;
        foreach ($allowedRoles as $role) {
            if (is_numeric($role) && $userRoleId !== null) {
                if ((int)$role === $userRoleId) {
                    $allowed = true;
                    break;
                }
            } elseif (is_string($role) && $userRoleName !== null) {
                if ($role === $userRoleName) {
                    $allowed = true;
                    break;
                }
            }
        }

        if (!$allowed) {
            http_response_code(403);
            echo json_encode([
                "error" => "Acceso denegado",
                "message" => "No tienes permisos para acceder a este recurso",
                "required_roles" => $allowedRoles,
                "your_role" => $userRoleName,
                "your_role_id" => $userRoleId
            ]);
            exit;
        }

        return true;
    }
}

