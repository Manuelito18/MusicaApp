<?php

namespace app\config;

use PDO;
use PDOException;

class Database
{
    // Datos actualizados según tus nuevas variables de Neon
    private $host = "ep-purple-rice-ago0vkfx-pooler.c-2.eu-central-1.aws.neon.tech";
    private $port = "5432";
    private $db_name = "neondb";
    private $username = "neondb_owner";
    private $password = "npg_z8q9iCBvQdjE";
    private $conn;

    public function __construct()
    {
    }

    public function connect()
    {
        $this->conn = null;

        try {
            // Se integran PGSSLMODE y PGCHANNELBINDING en el DSN
            $dsn = "pgsql:host=" . $this->host . 
                   ";port=" . $this->port . 
                   ";dbname=" . $this->db_name . 
                   ";sslmode=require" . 
                   ";channel_binding=require";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configuración recomendada para manejo de errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Lanza la excepción para capturarla en el index o controller principal
            throw new PDOException("Error de Conexión: " . $e->getMessage(), (int)$e->getCode());
        }

        return $this->conn;
    }
}