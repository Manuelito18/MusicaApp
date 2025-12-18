<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../models/ProductoModel.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method !== 'GET') {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        exit;
    }

    if (isset($_GET['categoriaId']) && is_numeric($_GET['categoriaId'])) {
        $categoriaId = (int)$_GET['categoriaId'];
        $productos = ProductoModel::getByCategory($categoriaId);
        http_response_code(200);
        echo json_encode($productos);
        exit;
    }

    $productos = ProductoModel::getAll();
    http_response_code(200);
    echo json_encode($productos);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}


