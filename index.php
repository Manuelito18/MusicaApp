<?php

// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// Autoloader simple
spl_autoload_register(function ($class) {
  $prefix = 'App\\';
  $base_dir = __DIR__ . '/app/';

  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    return;
  }

  $relative_class = substr($class, $len);
  $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

  if (file_exists($file)) {
    require $file;
  }
});

use App\Controllers\ProductoController;

// Router muy básico
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Asumimos que la API está en /api/productos
// Si el script se ejecuta en localhost:8000, la URI será /api/productos...

if ((isset($uri[1]) && $uri[1] === 'api') && (isset($uri[2]) && $uri[2] === 'productos')) {

  $controller = new ProductoController();
  $requestMethod = $_SERVER["REQUEST_METHOD"];
  $id = isset($uri[3]) ? (int) $uri[3] : null;

  switch ($requestMethod) {
    case 'GET':
      if ($id) {
        $controller->show($id);
      } else {
        $controller->index();
      }
      break;
    case 'POST':
      $controller->store();
      break;
    case 'PUT':
      if ($id) {
        $controller->update($id);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'ID requerido para actualizar']);
      }
      break;
    case 'DELETE':
      if ($id) {
        $controller->delete($id);
      } else {
        http_response_code(400);
        echo json_encode(['message' => 'ID requerido para eliminar']);
      }
      break;
    default:
      http_response_code(405);
      echo json_encode(['message' => 'Método no permitido']);
      break;
  }
} else {
  // Ruta por defecto o 404
  if ($uri[1] == "") {
    echo json_encode(['message' => 'MusicShop API Running']);
  } else {
    http_response_code(404);
    echo json_encode(['message' => 'Ruta no encontrada']);
  }
}
