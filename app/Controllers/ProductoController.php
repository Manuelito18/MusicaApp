<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class ProductoController
{
  private $model;

  public function __construct()
  {
    $this->model = new ProductoModel();
  }

  public function index()
  {
    $productos = $this->model->getAll();
    echo json_encode($productos);
  }

  public function show($id)
  {
    $producto = $this->model->getById($id);
    if ($producto) {
      echo json_encode($producto);
    } else {
      http_response_code(404);
      echo json_encode(['message' => 'Producto no encontrado']);
    }
  }

  public function store()
  {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($this->validate($data)) {
      $id = $this->model->create($data);
      if ($id) {
        http_response_code(201);
        echo json_encode(['message' => 'Producto creado', 'id' => $id]);
      } else {
        http_response_code(500);
        echo json_encode(['message' => 'Error al crear producto']);
      }
    } else {
      http_response_code(400);
      echo json_encode(['message' => 'Datos inválidos']);
    }
  }

  public function update($id)
  {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($this->validate($data)) {
      if ($this->model->update($id, $data)) {
        echo json_encode(['message' => 'Producto actualizado']);
      } else {
        http_response_code(500);
        echo json_encode(['message' => 'Error al actualizar producto']);
      }
    } else {
      http_response_code(400);
      echo json_encode(['message' => 'Datos inválidos']);
    }
  }

  public function delete($id)
  {
    if ($this->model->delete($id)) {
      echo json_encode(['message' => 'Producto eliminado (lógico)']);
    } else {
      http_response_code(500);
      echo json_encode(['message' => 'Error al eliminar producto']);
    }
  }

  private function validate($data)
  {
    return isset($data['nombre']) &&
      isset($data['precio']) &&
      isset($data['stock']) &&
      isset($data['id_categoria']) &&
      isset($data['id_marca']) &&
      isset($data['id_estado']);
  }
}
