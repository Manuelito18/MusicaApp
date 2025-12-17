<?php
require_once __DIR__ . '/../models/MarcaModel.php';

class MarcaController {

    public static function index() {
        http_response_code(200);
        echo json_encode(MarcaModel::getAll());
    }

    public static function show($id) {
        $marca = MarcaModel::getById($id);

        if (!$marca) {
            http_response_code(404);
            echo json_encode(["error" => "Marca no encontrada"]);
            return;
        }

        http_response_code(200);
        echo json_encode($marca);
    }
}

