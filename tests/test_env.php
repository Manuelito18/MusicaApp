<?php

// Script de diagnóstico para verificar las variables de entorno

require_once __DIR__ . '/app/Config/Env.php';

use App\Config\Env;

echo "=== Diagnóstico de Variables de Entorno ===\n\n";

// Cargar el archivo .env
try {
  Env::load(__DIR__ . '/.env');
  echo "✓ Archivo .env cargado correctamente\n\n";
} catch (Exception $e) {
  echo "✗ Error al cargar .env: " . $e->getMessage() . "\n";
  exit(1);
}

// Mostrar las variables de la base de datos
echo "Variables de Base de Datos:\n";
echo "DB_HOST: '" . Env::get('DB_HOST', 'NO DEFINIDO') . "'\n";
echo "DB_PORT: '" . Env::get('DB_PORT', 'NO DEFINIDO') . "'\n";
echo "DB_NAME: '" . Env::get('DB_NAME', 'NO DEFINIDO') . "'\n";
echo "DB_USER: '" . Env::get('DB_USER', 'NO DEFINIDO') . "'\n";
echo "DB_PASS: '" . Env::get('DB_PASS', 'NO DEFINIDO') . "'\n\n";

// Construir el DSN
$host = Env::get('DB_HOST', 'localhost');
$port = Env::get('DB_PORT', '5432');
$dbname = Env::get('DB_NAME', 'musicshopdb');

echo "DSN construido:\n";
$dsn = "pgsql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
echo $dsn . "\n\n";

// Verificar si hay caracteres especiales
echo "Verificación de caracteres especiales:\n";
echo "DB_HOST contiene '#': " . (strpos($host, '#') !== false ? 'SÍ ⚠️' : 'NO ✓') . "\n";
echo "DB_PORT contiene '#': " . (strpos($port, '#') !== false ? 'SÍ ⚠️' : 'NO ✓') . "\n";
echo "DB_NAME contiene '#': " . (strpos($dbname, '#') !== false ? 'SÍ ⚠️' : 'NO ✓') . "\n\n";

// Intentar conectar
echo "Intentando conectar a la base de datos...\n";
try {
  require_once __DIR__ . '/app/Config/Database.php';
  $db = new App\Config\Database();
  $conn = $db->connect();
  echo "✓ Conexión exitosa!\n";
} catch (Exception $e) {
  echo "✗ Error de conexión: " . $e->getMessage() . "\n";
}
