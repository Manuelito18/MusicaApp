<?php
require_once __DIR__ . '/../models/ProductoModel.php';

class ProductoController {

    public static function index() {
        http_response_code(200);
        echo json_encode(ProductoModel::getAll());
    }

    public static function show($id) {
        $producto = ProductoModel::getById($id);

        if (!$producto) {
            http_response_code(404);
            echo json_encode(["error" => "Producto no encontrado"]);
            return;
        }

        http_response_code(200);
        echo json_encode($producto);
    }

    public static function getByCategory($idCategoria) {
        $productos = ProductoModel::getByCategory($idCategoria);

        if (empty($productos)) {
            http_response_code(404);
            echo json_encode(["error" => "No se encontraron productos en esta categoría"]);
            return;
        }

        http_response_code(200);
        echo json_encode($productos);
    }

    public static function store() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos"]);
            return;
        }

        ProductoModel::create($data);
        http_response_code(201);
        echo json_encode(["message" => "Producto creado correctamente"]);
    }

    public static function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        ProductoModel::update($id, $data);
        http_response_code(200);
        echo json_encode(["message" => "Producto actualizado"]);
    }

    public static function updateStock($id) {
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

    public static function destroy($id) {
        ProductoModel::delete($id);
        http_response_code(200);
        echo json_encode(["message" => "Producto eliminado"]);
    }
}
