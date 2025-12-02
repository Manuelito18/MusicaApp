<?php

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

// Cargar variables de entorno
use App\Config\Env;

try {
  Env::load(__DIR__ . '/.env');
} catch (\RuntimeException $e) {
  // Si no existe el .env, continuar con valores por defecto
  error_log("Warning: " . $e->getMessage());
}

// Configuración de CORS desde variables de entorno
$allowedOrigins = Env::get('CORS_ALLOWED_ORIGINS', '*');
$originsArray = $allowedOrigins === '*' ? ['*'] : explode(',', $allowedOrigins);

// Si el origen de la petición está en la lista permitida, usarlo
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array('*', $originsArray) || in_array($origin, $originsArray)) {
  header("Access-Control-Allow-Origin: " . ($origin ?: '*'));
}

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}


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
