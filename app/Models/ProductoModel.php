<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class ProductoModel
{
  private $conn;
  private $table = 'Producto';

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->connect();
  }

  public function getAll()
  {
    $query = "SELECT 
                    p.IdProducto as \"IdProducto\", 
                    p.Nombre as \"Nombre\", 
                    p.Descripcion as \"Descripcion\", 
                    p.Precio as \"Precio\", 
                    p.Stock as \"Stock\", 
                    p.ImagenURL as \"ImagenURL\",
                    c.Nombre as \"Categoria\",
                    m.Nombre as \"Marca\",
                    e.Nombre as \"Estado\"
                  FROM " . $this->table . " p
                  LEFT JOIN Categoria c ON p.IdCategoria = c.IdCategoria
                  LEFT JOIN Marca m ON p.IdMarca = m.IdMarca
                  LEFT JOIN EstadoProducto e ON p.IdEstadoProducto = e.IdEstadoProducto
                  WHERE p.IdEstadoProducto != 3 -- Excluir descontinuados/eliminados lógicos si se desea
                  ORDER BY p.IdProducto DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function getById($id)
  {
    $query = "SELECT 
                IdProducto as \"IdProducto\",
                Nombre as \"Nombre\",
                Descripcion as \"Descripcion\",
                Precio as \"Precio\",
                Stock as \"Stock\",
                IdCategoria as \"IdCategoria\",
                IdMarca as \"IdMarca\",
                IdEstadoProducto as \"IdEstadoProducto\",
                ImagenURL as \"ImagenURL\"
              FROM " . $this->table . " WHERE IdProducto = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
  }

  public function create($data)
  {
    $query = "INSERT INTO " . $this->table . " 
                  (Nombre, Descripcion, Precio, Stock, IdCategoria, IdMarca, IdEstadoProducto, ImagenURL) 
                  VALUES 
                  (:nombre, :descripcion, :precio, :stock, :id_categoria, :id_marca, :id_estado, :imagen_url)
                  RETURNING IdProducto";

    $stmt = $this->conn->prepare($query);

    // Sanitize and bind
    $stmt->bindParam(':nombre', $data['nombre']);
    $stmt->bindParam(':descripcion', $data['descripcion']);
    $stmt->bindParam(':precio', $data['precio']);
    $stmt->bindParam(':stock', $data['stock']);
    $stmt->bindParam(':id_categoria', $data['id_categoria']);
    $stmt->bindParam(':id_marca', $data['id_marca']);
    $stmt->bindParam(':id_estado', $data['id_estado']);
    $stmt->bindParam(':imagen_url', $data['imagen_url']);

    if ($stmt->execute()) {
      return $stmt->fetchColumn();
    }
    return false;
  }

  public function update($id, $data)
  {
    $query = "UPDATE " . $this->table . " 
                  SET Nombre = :nombre, 
                      Descripcion = :descripcion, 
                      Precio = :precio, 
                      Stock = :stock, 
                      IdCategoria = :id_categoria, 
                      IdMarca = :id_marca, 
                      IdEstadoProducto = :id_estado,
                      ImagenURL = :imagen_url
                  WHERE IdProducto = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $data['nombre']);
    $stmt->bindParam(':descripcion', $data['descripcion']);
    $stmt->bindParam(':precio', $data['precio']);
    $stmt->bindParam(':stock', $data['stock']);
    $stmt->bindParam(':id_categoria', $data['id_categoria']);
    $stmt->bindParam(':id_marca', $data['id_marca']);
    $stmt->bindParam(':id_estado', $data['id_estado']);
    $stmt->bindParam(':imagen_url', $data['imagen_url']);

    return $stmt->execute();
  }

  public function delete($id)
  {
    // Borrado lógico: Cambiar estado a 3 (Descontinuado)
    $query = "UPDATE " . $this->table . " SET IdEstadoProducto = 3 WHERE IdProducto = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
  }
}
