<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct()
    {
        // Cargar variables de entorno
        $this->host = Env::get('DB_HOST', 'localhost');
        $this->port = Env::get('DB_PORT', '5432');
        $this->db_name = Env::get('DB_NAME', 'musicshopdb');
        $this->username = Env::get('DB_USER', 'postgres');
        $this->password = Env::get('DB_PASS', '');
    }

    public function connect()
    {
        $this->conn = null;

        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
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
