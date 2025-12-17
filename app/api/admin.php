<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../controllers/AdminController.php';

$method = $_SERVER['REQUEST_METHOD'];

// Obtener la ruta desde query string o PATH_INFO
$path = '';
if (isset($_GET['path'])) {
    $path = $_GET['path'];
} elseif (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
    $path = trim($_SERVER['PATH_INFO'], '/');
} else {
    // Intentar desde REQUEST_URI
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $path = str_replace($scriptName, '', $requestUri);
    $path = parse_url($path, PHP_URL_PATH);
    $path = trim($path, '/');
}

$pathParts = explode('/', $path);
$pathParts = array_filter($pathParts); // Remover elementos vacíos
$pathParts = array_values($pathParts); // Reindexar

try {
    // Rutas de usuarios
    if ($pathParts[0] === 'usuarios') {
        if ($method === 'GET' && count($pathParts) === 1) {
            AdminController::getUsuarios();
        } elseif ($method === 'GET' && count($pathParts) === 2) {
            AdminController::getUsuario($pathParts[1]);
        } elseif ($method === 'POST' && count($pathParts) === 1) {
            AdminController::createUsuario();
        } elseif ($method === 'PUT' && count($pathParts) === 3 && $pathParts[2] === 'rol') {
            AdminController::updateUsuarioRol($pathParts[1]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Ruta no encontrada"]);
        }
    }
    // Rutas de productos
    elseif ($pathParts[0] === 'productos') {
        if ($method === 'GET' && count($pathParts) === 1) {
            AdminController::getProductos();
        } elseif ($method === 'GET' && count($pathParts) === 2) {
            AdminController::getProducto($pathParts[1]);
        } elseif ($method === 'POST' && count($pathParts) === 1) {
            AdminController::createProducto();
        } elseif ($method === 'PUT' && count($pathParts) === 2) {
            AdminController::updateProducto($pathParts[1]);
        } elseif ($method === 'PUT' && count($pathParts) === 3 && $pathParts[2] === 'stock') {
            AdminController::updateProductoStock($pathParts[1]);
        } elseif ($method === 'DELETE' && count($pathParts) === 2) {
            AdminController::deleteProducto($pathParts[1]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Ruta no encontrada"]);
        }
    }
    // Rutas de ventas
    elseif ($pathParts[0] === 'ventas') {
        if ($method === 'GET' && count($pathParts) === 1) {
            AdminController::getVentas();
        } elseif ($method === 'GET' && count($pathParts) === 2) {
            AdminController::getVenta($pathParts[1]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Ruta no encontrada"]);
        }
    }
    // Estadísticas
    elseif ($pathParts[0] === 'estadisticas' && $method === 'GET') {
        AdminController::getEstadisticas();
    }
    // Categorías y marcas (para formularios)
    elseif ($pathParts[0] === 'categorias' && $method === 'GET') {
        AdminController::getCategorias();
    }
    elseif ($pathParts[0] === 'marcas' && $method === 'GET') {
        AdminController::getMarcas();
    }
    // Roles
    elseif ($pathParts[0] === 'roles' && $method === 'GET') {
        AdminController::getRoles();
    }
    else {
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

