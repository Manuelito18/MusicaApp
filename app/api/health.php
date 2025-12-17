<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/Database.php';

use app\config\Database;

try {
    $db = new Database();
    $conn = $db->connect();
    
    // Test simple de conexión
    $stmt = $conn->query("SELECT 1 as test");
    $result = $stmt->fetch();
    
    // Obtener información de la base de datos
    $version = $conn->query("SELECT version() as version")->fetch()['version'];
    
    http_response_code(200);
    echo json_encode([
        "status" => "ok",
        "database" => "connected",
        "message" => "Conexión a la base de datos exitosa",
        "version" => $version
    ]);
} catch (PDOException $e) {
    http_response_code(503);
    echo json_encode([
        "status" => "error",
        "database" => "disconnected",
        "message" => "Error de conexión a la base de datos",
        "error" => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "database" => "unknown",
        "message" => "Error desconocido",
        "error" => $e->getMessage()
    ]);
}

