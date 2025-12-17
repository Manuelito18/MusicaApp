<?php
require_once __DIR__ . '/../../models/UsuarioModel.php';
require_once __DIR__ . '/../../utils/JWTHelper.php';

class AuthController {

    public static function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "Username y password son requeridos"]);
            return;
        }

        $username = $data['username'];
        $password = $data['password'];

        // Buscar usuario por username
        $usuario = UsuarioModel::getByUsername($username);

        if (!$usuario) {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales inválidas"]);
            return;
        }

        // Validar hash de contraseña
        if (!password_verify($password, $usuario['PasswordHash'])) {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales inválidas"]);
            return;
        }

        // Generar JWT
        $payload = [
            'id' => $usuario['IdUsuario'],
            'username' => $usuario['Username'],
            'rol' => $usuario['Rol'],
            'idRol' => $usuario['IdRol'],
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) // 24 horas
        ];

        $token = JWTHelper::generate($payload);

        http_response_code(200);
        echo json_encode([
            "message" => "Login exitoso",
            "token" => $token,
            "user" => [
                "id" => $usuario['IdUsuario'],
                "username" => $usuario['Username'],
                "rol" => $usuario['Rol'],
                "idRol" => $usuario['IdRol']
            ]
        ]);
    }

    public static function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username']) || !isset($data['password']) || !isset($data['email'])) {
            http_response_code(400);
            echo json_encode(["error" => "Username, password y email son requeridos"]);
            return;
        }

        // Verificar si el username ya existe
        $existingUser = UsuarioModel::getByUsername($data['username']);
        if ($existingUser) {
            http_response_code(400);
            echo json_encode(["error" => "El username ya está en uso"]);
            return;
        }

        // Hash de contraseña
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        // Crear UserData primero si se proporcionan datos adicionales
        $idUserData = null;
        if (isset($data['nombres']) && isset($data['apellidos'])) {
            require_once __DIR__ . '/../../models/UserDataModel.php';
            
            // Verificar si el email ya existe
            $existingEmail = UserDataModel::getByEmail($data['email']);
            if ($existingEmail) {
                http_response_code(400);
                echo json_encode(["error" => "El email ya está en uso"]);
                return;
            }

            $userData = [
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'idTipoDocumento' => $data['idTipoDocumento'] ?? 1, // DNI por defecto
                'numeroDocumento' => $data['numeroDocumento'] ?? '',
                'email' => $data['email'],
                'telefono' => $data['telefono'] ?? null
            ];
            $idUserData = UserDataModel::create($userData);
        }

        // Crear usuario (rol Cliente por defecto = 3 según data.sql)
        $usuarioData = [
            'username' => $data['username'],
            'passwordHash' => $passwordHash,
            'idRol' => $data['idRol'] ?? 3, // Cliente por defecto
            'idUserData' => $idUserData
        ];

        $idUsuario = UsuarioModel::create($usuarioData);

        http_response_code(201);
        echo json_encode([
            "message" => "Usuario registrado correctamente",
            "idUsuario" => $idUsuario
        ]);
    }

    public static function validateCredentials($username, $password) {
        $usuario = UsuarioModel::getByUsername($username);
        
        if (!$usuario) {
            return false;
        }

        return password_verify($password, $usuario['PasswordHash']);
    }
}

