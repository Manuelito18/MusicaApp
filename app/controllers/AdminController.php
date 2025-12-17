<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/UserDataModel.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/CategoriaModel.php';
require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/RoleMiddleware.php';

class AdminController {

    // ============================================
    // USUARIOS
    // ============================================

    public static function getUsuarios() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $usuarios = UsuarioModel::getAll();
        http_response_code(200);
        echo json_encode($usuarios);
    }

    public static function getUsuario($id) {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $usuario = UsuarioModel::getById($id);
        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }
        http_response_code(200);
        echo json_encode($usuario);
    }

    public static function createUsuario() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "Username y password son requeridos"]);
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

        // Crear UserData si se proporcionan datos adicionales
        $idUserData = null;
        if (isset($data['nombres']) && isset($data['apellidos']) && isset($data['email'])) {
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
                'idTipoDocumento' => $data['idTipoDocumento'] ?? 1,
                'numeroDocumento' => $data['numeroDocumento'] ?? '',
                'email' => $data['email'],
                'telefono' => $data['telefono'] ?? null
            ];
            $idUserData = UserDataModel::create($userData);
        }

        // Crear usuario (rol por defecto: Trabajador = 2, Cliente = 3)
        $idRol = $data['idRol'] ?? 2; // Trabajador por defecto
        $usuarioData = [
            'username' => $data['username'],
            'passwordHash' => $passwordHash,
            'idRol' => $idRol,
            'idUserData' => $idUserData
        ];

        $idUsuario = UsuarioModel::create($usuarioData);

        http_response_code(201);
        echo json_encode([
            "message" => "Usuario creado correctamente",
            "idUsuario" => $idUsuario
        ]);
    }

    public static function updateUsuarioRol($id) {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['idRol']) || !is_numeric($data['idRol'])) {
            http_response_code(400);
            echo json_encode(["error" => "idRol es requerido y debe ser numérico"]);
            return;
        }

        $result = UsuarioModel::updateRole($id, $data['idRol']);

        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Rol actualizado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el rol"]);
        }
    }

    // ============================================
    // PRODUCTOS
    // ============================================

    public static function getProductos() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $productos = ProductoModel::getAll();
        http_response_code(200);
        echo json_encode($productos);
    }

    public static function getProducto($id) {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $producto = ProductoModel::getById($id);
        if (!$producto) {
            http_response_code(404);
            echo json_encode(["error" => "Producto no encontrado"]);
            return;
        }
        http_response_code(200);
        echo json_encode($producto);
    }

    public static function createProducto() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos"]);
            return;
        }

        $result = ProductoModel::create($data);
        if ($result) {
            http_response_code(201);
            echo json_encode(["message" => "Producto creado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el producto"]);
        }
    }

    public static function updateProducto($id) {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $data = json_decode(file_get_contents("php://input"), true);

        $result = ProductoModel::update($id, $data);
        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Producto actualizado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el producto"]);
        }
    }

    public static function updateProductoStock($id) {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['stock']) || !is_numeric($data['stock'])) {
            http_response_code(400);
            echo json_encode(["error" => "Stock inválido"]);
            return;
        }

        $result = ProductoModel::updateStock($id, $data['stock']);
        
        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Stock actualizado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el stock"]);
        }
    }

    public static function deleteProducto($id) {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $result = ProductoModel::delete($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Producto eliminado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el producto"]);
        }
    }

    // ============================================
    // VENTAS/PEDIDOS
    // ============================================

    public static function getVentas() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $ventas = PedidoModel::getAll();
        http_response_code(200);
        echo json_encode($ventas);
    }

    public static function getVenta($id) {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $pedido = PedidoModel::getById($id);
        if (!$pedido) {
            http_response_code(404);
            echo json_encode(["error" => "Pedido no encontrado"]);
            return;
        }

        $detalles = PedidoModel::getDetallesByPedidoId($id);
        $pedido['detalles'] = $detalles;

        http_response_code(200);
        echo json_encode($pedido);
    }

    public static function getEstadisticas() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $estadisticas = PedidoModel::getTotalVentas();
        http_response_code(200);
        echo json_encode($estadisticas);
    }

    // ============================================
    // CATEGORÍAS Y MARCAS (para formularios)
    // ============================================

    public static function getCategorias() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        require_once __DIR__ . '/CategoriaController.php';
        CategoriaController::index();
    }

    public static function getMarcas() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        require_once __DIR__ . '/MarcaController.php';
        MarcaController::index();
    }

    public static function getRoles() {
        AuthMiddleware::handle();
        RoleMiddleware::handle(['Administrador']);
        
        $db = Database::connect();
        $sql = "SELECT \"IdRol\", \"Nombre\" FROM \"Rol\" ORDER BY \"IdRol\"";
        $stmt = $db->query($sql);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode($roles);
    }
}

