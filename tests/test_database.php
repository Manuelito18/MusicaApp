<?php

/**
 * Test de conexión a la base de datos
 * 
 * Este script verifica que la conexión a la base de datos funcione correctamente
 */

require_once __DIR__ . '/../app/config/Database.php';

use app\config\Database;

echo "=== Test de Conexión a Base de Datos ===\n\n";

try {
    // Crear instancia de Database
    $db = new Database();
    echo "✓ Instancia de Database creada correctamente\n";
    
    // Intentar conectar
    echo "Intentando conectar a la base de datos...\n";
    $conn = $db->connect();
    echo "✓ Conexión exitosa a la base de datos\n\n";
    
    // Verificar información de la conexión
    echo "Información de la conexión:\n";
    $serverInfo = $conn->getAttribute(PDO::ATTR_SERVER_INFO);
    echo "  - Servidor: " . $serverInfo . "\n";
    echo "  - Versión: " . $conn->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n\n";
    
    // Test simple: consultar una tabla del sistema
    echo "Realizando consulta de prueba...\n";
    $stmt = $conn->query("SELECT version() as version");
    $result = $stmt->fetch();
    echo "✓ PostgreSQL version: " . $result['version'] . "\n\n";
    
    // Verificar si existen tablas
    echo "Verificando tablas existentes...\n";
    $stmt = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    $tables = $stmt->fetchAll();
    
    if (count($tables) > 0) {
        echo "✓ Tablas encontradas: " . count($tables) . "\n";
        foreach ($tables as $table) {
            echo "  - " . $table['table_name'] . "\n";
        }
    } else {
        echo "⚠ No se encontraron tablas en la base de datos\n";
        echo "  Ejecuta el script setup_database.php para crear las tablas\n";
    }
    
    echo "\n✓ Test completado exitosamente\n";
    
} catch (PDOException $e) {
    echo "✗ Error de conexión: " . $e->getMessage() . "\n";
    echo "  Código: " . $e->getCode() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

