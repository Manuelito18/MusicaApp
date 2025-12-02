<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $db_name = 'musicshopdb'; // Cambiar si es necesario
    private $username = 'manuelinux'; // Cambiar según credenciales
    private $password = 'manulinux'; // Cambiar según credenciales
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $dsn = "pgsql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Lanzar la excepción para que sea capturada por el log de errores de PHP
            // y evitar devolver un objeto null que cause errores fatales después.
            throw new PDOException("Connection Error: " . $e->getMessage(), (int)$e->getCode());
        }

        return $this->conn;
    }
}
