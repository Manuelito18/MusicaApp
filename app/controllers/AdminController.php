<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/UserDataModel.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/TrabajadorModel.php';
require_once __DIR__ . '/../models/CategoriaModel.php';
require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/RoleMiddleware.php';

class AdminController {

    private static function requireAdmin() {
        AuthMiddleware::handle();
        // RBAC por role_id = 1 (Admin)
        RoleMiddleware::handle([1]);
    }

    private static function sanitizeString($value) {
        if ($value === null) return null;
        $value = trim((string)$value);
        $value = strip_tags($value);
        return $value;
    }

    // ============================================
    // USUARIOS
    // ============================================

    public static function getUsuarios() {
        self::requireAdmin();
        
        $usuarios = UsuarioModel::getAll();
        http_response_code(200);
        echo json_encode($usuarios);
    }

    public static function getUsuario($id) {
        self::requireAdmin();
        
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
        self::requireAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "Username y password son requeridos"]);
            return;
        }

        // Verificar si el username ya existe
        $username = self::sanitizeString($data['username']);
        $existingUser = UsuarioModel::getByUsername($username);
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
            $email = self::sanitizeString($data['email']);
            $existingEmail = UserDataModel::getByEmail($email);
            if ($existingEmail) {
                http_response_code(400);
                echo json_encode(["error" => "El email ya está en uso"]);
                return;
            }

            $userData = [
                'nombres' => self::sanitizeString($data['nombres']),
                'apellidos' => self::sanitizeString($data['apellidos']),
                'idTipoDocumento' => $data['idTipoDocumento'] ?? 1,
                'numeroDocumento' => self::sanitizeString($data['numeroDocumento'] ?? ''),
                'email' => $email,
                'telefono' => self::sanitizeString($data['telefono'] ?? null)
            ];
            $idUserData = UserDataModel::create($userData);
        }

        // Crear usuario (rol por defecto: Trabajador = 2, Cliente = 3)
        $idRol = $data['idRol'] ?? 2; // Trabajador por defecto
        $usuarioData = [
            'username' => $username,
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
        self::requireAdmin();
        
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
        self::requireAdmin();
        
        $productos = ProductoModel::getAll();
        http_response_code(200);
        echo json_encode($productos);
    }

    public static function getProducto($id) {
        self::requireAdmin();
        
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
        self::requireAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos"]);
            return;
        }

        // Sanitización básica (JSON output, pero limpiamos inputs)
        $data['nombre'] = self::sanitizeString($data['nombre'] ?? '');
        $data['descripcion'] = self::sanitizeString($data['descripcion'] ?? '');
        $data['imagen'] = self::sanitizeString($data['imagen'] ?? '');
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
        self::requireAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);

        $data['nombre'] = self::sanitizeString($data['nombre'] ?? '');
        $data['descripcion'] = self::sanitizeString($data['descripcion'] ?? '');
        $data['imagen'] = self::sanitizeString($data['imagen'] ?? '');

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
        self::requireAdmin();
        
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
        self::requireAdmin();
        
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
        self::requireAdmin();
        
        // Venta ~= Pedido
        $ventas = VentaModel::getAll();
        http_response_code(200);
        echo json_encode($ventas);
    }

    public static function getVenta($id) {
        self::requireAdmin();
        
        $venta = VentaModel::getById($id);
        if (!$venta) {
            http_response_code(404);
            echo json_encode(["error" => "Pedido no encontrado"]);
            return;
        }

        $detalles = VentaModel::getDetallesByVentaId($id);
        $venta['detalles'] = $detalles;

        http_response_code(200);
        echo json_encode($venta);
    }

    public static function getEstadisticas() {
        self::requireAdmin();
        
        $ventas = VentaModel::getDashboardMetrics();
        $trab = TrabajadorModel::countAll();

        // Conteo de productos
        $db = Database::connect();
        $prod = $db->query("SELECT COUNT(*) AS \"TotalProductos\" FROM producto")->fetch(PDO::FETCH_ASSOC);

        $estadisticas = array_merge($ventas ?: [], $prod ?: [], $trab ?: []);
        http_response_code(200);
        echo json_encode($estadisticas);
    }

    // ============================================
    // TRABAJADORES (CRUD)
    // ============================================

    public static function getTrabajadores() {
        self::requireAdmin();
        $trabajadores = TrabajadorModel::getAll();
        http_response_code(200);
        echo json_encode($trabajadores);
    }

    public static function createTrabajador() {
        self::requireAdmin();
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "Username y password son requeridos"]);
            return;
        }

        $username = self::sanitizeString($data['username']);
        $existingUser = UsuarioModel::getByUsername($username);
        if ($existingUser) {
            http_response_code(400);
            echo json_encode(["error" => "El username ya está en uso"]);
            return;
        }

        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        // Preparar userdata en el formato del model
        $payload = [
            'username' => $username,
            'passwordHash' => $passwordHash,
            'nombres' => self::sanitizeString($data['nombres'] ?? ''),
            'apellidos' => self::sanitizeString($data['apellidos'] ?? ''),
            'email' => self::sanitizeString($data['email'] ?? ''),
            'telefono' => self::sanitizeString($data['telefono'] ?? null),
            'numeroDocumento' => self::sanitizeString($data['numeroDocumento'] ?? ''),
            'idTipoDocumento' => $data['idTipoDocumento'] ?? 1
        ];

        if ($payload['email'] !== '') {
            $existingEmail = UserDataModel::getByEmail($payload['email']);
            if ($existingEmail) {
                http_response_code(400);
                echo json_encode(["error" => "El email ya está en uso"]);
                return;
            }
        }

        $idUsuario = TrabajadorModel::create($payload);
        http_response_code(201);
        echo json_encode(["message" => "Trabajador creado correctamente", "idUsuario" => $idUsuario]);
    }

    public static function updateTrabajador($id) {
        self::requireAdmin();
        $data = json_decode(file_get_contents("php://input"), true);

        $payload = [];
        if (isset($data['username'])) $payload['username'] = self::sanitizeString($data['username']);
        if (isset($data['password']) && $data['password'] !== '') {
            $payload['passwordHash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (isset($data['userData']) && is_array($data['userData'])) {
            $payload['userData'] = [
                'nombres' => self::sanitizeString($data['userData']['nombres'] ?? ''),
                'apellidos' => self::sanitizeString($data['userData']['apellidos'] ?? ''),
                'idTipoDocumento' => $data['userData']['idTipoDocumento'] ?? 1,
                'numeroDocumento' => self::sanitizeString($data['userData']['numeroDocumento'] ?? ''),
                'email' => self::sanitizeString($data['userData']['email'] ?? ''),
                'telefono' => self::sanitizeString($data['userData']['telefono'] ?? null),
            ];
        }

        TrabajadorModel::update($id, $payload);
        http_response_code(200);
        echo json_encode(["message" => "Trabajador actualizado correctamente"]);
    }

    public static function deleteTrabajador($id) {
        self::requireAdmin();
        $ok = TrabajadorModel::delete($id);
        if ($ok) {
            http_response_code(200);
            echo json_encode(["message" => "Trabajador eliminado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el trabajador"]);
        }
    }

    // ============================================
    // CATEGORÍAS Y MARCAS (para formularios)
    // ============================================

    public static function getCategorias() {
        self::requireAdmin();
        
        require_once __DIR__ . '/CategoriaController.php';
        CategoriaController::index();
    }

    public static function getMarcas() {
        self::requireAdmin();
        
        require_once __DIR__ . '/MarcaController.php';
        MarcaController::index();
    }

    public static function getRoles() {
        self::requireAdmin();
        
        $db = Database::connect();
        $sql = "SELECT idrol AS \"IdRol\", nombre AS \"Nombre\" FROM rol ORDER BY idrol";
        $stmt = $db->query($sql);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode($roles);
    }
}

