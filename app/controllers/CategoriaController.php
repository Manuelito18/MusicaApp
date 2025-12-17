<?php
require_once __DIR__ . '/../models/CategoriaModel.php';

class CategoriaController {

    public static function index() {
        http_response_code(200);
        echo json_encode(CategoriaModel::getAll());
    }

    public static function show($id) {
        $categoria = CategoriaModel::getById($id);

        if (!$categoria) {
            http_response_code(404);
            echo json_encode(["error" => "Categoría no encontrada"]);
            return;
        }

        http_response_code(200);
        echo json_encode($categoria);
    }
}

