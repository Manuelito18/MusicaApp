<?php

/**
 * Script para configurar y cargar datos en la base de datos
 * 
 * Este script:
 * 1. Lee el archivo crear.sql y ejecuta todas las sentencias CREATE TABLE
 * 2. Lee el archivo data.sql y ejecuta todas las sentencias INSERT
 * 
 * Uso: php database/setup_database.php
 */

require_once __DIR__ . '/../app/config/Database.php';

use app\config\Database;

// Configuración
$crearSqlFile = __DIR__ . '/crear.sql';
$dataSqlFile = __DIR__ . '/data.sql';

echo "=== Configuración de Base de Datos ===\n\n";

// Verificar que existan los archivos SQL
if (!file_exists($crearSqlFile)) {
    die("✗ Error: No se encontró el archivo crear.sql en: {$crearSqlFile}\n");
}

if (!file_exists($dataSqlFile)) {
    die("✗ Error: No se encontró el archivo data.sql en: {$dataSqlFile}\n");
}

try {
    // Conectar a la base de datos
    echo "Conectando a la base de datos...\n";
    $db = new Database();
    $conn = $db->connect();
    echo "✓ Conexión exitosa\n\n";
    
    // Función para ejecutar un archivo SQL
    function executeSqlFile($conn, $filePath, $description) {
        echo "Procesando: {$description}\n";
        echo "Archivo: {$filePath}\n";
        
        $sql = file_get_contents($filePath);
        
        if ($sql === false) {
            throw new Exception("No se pudo leer el archivo: {$filePath}");
        }
        
        // Dividir el SQL en sentencias individuales
        // Eliminar comentarios de múltiples líneas
        $sql = preg_replace('/--.*$/m', '', $sql);
        
        // Dividir por punto y coma, pero tener cuidado con strings que contengan punto y coma
        $statements = [];
        $currentStatement = '';
        $inString = false;
        $stringChar = '';
        
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            $nextChar = ($i < strlen($sql) - 1) ? $sql[$i + 1] : '';
            
            // Manejar comillas simples y dobles (incluyendo escape)
            if (($char === "'" || $char === '"') && ($i === 0 || $sql[$i - 1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                    $stringChar = '';
                }
            }
            
            $currentStatement .= $char;
            
            // Si encontramos un punto y coma fuera de una cadena, es el fin de una sentencia
            if ($char === ';' && !$inString) {
                $statement = trim($currentStatement);
                if (!empty($statement)) {
                    $statements[] = $statement;
                }
                $currentStatement = '';
            }
        }
        
        // Agregar la última sentencia si no terminó con punto y coma
        $remaining = trim($currentStatement);
        if (!empty($remaining)) {
            $statements[] = $remaining;
        }
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($statements as $index => $statement) {
            $statement = trim($statement);
            
            // Saltar sentencias vacías
            if (empty($statement) || strlen($statement) < 5) {
                continue;
            }
            
            try {
                // Ejecutar la sentencia
                $conn->exec($statement);
                $successCount++;
                
                // Mostrar progreso para sentencias importantes
                if (stripos($statement, 'CREATE TABLE') !== false) {
                    preg_match('/CREATE TABLE\s+(\w+)/i', $statement, $matches);
                    $tableName = isset($matches[1]) ? $matches[1] : 'desconocida';
                    echo "  ✓ Tabla creada: {$tableName}\n";
                } elseif (stripos($statement, 'INSERT INTO') !== false) {
                    preg_match('/INSERT INTO\s+(\w+)/i', $statement, $matches);
                    $tableName = isset($matches[1]) ? $matches[1] : 'desconocida';
                    echo "  ✓ Datos insertados en: {$tableName}\n";
                }
            } catch (PDOException $e) {
                $errorCount++;
                echo "  ✗ Error en sentencia " . ($index + 1) . ": " . $e->getMessage() . "\n";
                
                // Si es un error de "ya existe", continuar
                if (strpos($e->getMessage(), 'already exists') !== false || 
                    strpos($e->getMessage(), 'ya existe') !== false ||
                    strpos($e->getCode(), '42P07') !== false) {
                    echo "    (La tabla ya existe, se omite)\n";
                    $errorCount--; // No contar como error
                } else {
                    // Para otros errores, decidir si continuar o no
                    // Comentar la siguiente línea si quieres que se detenga en el primer error
                    // throw $e;
                }
            }
        }
        
        echo "\n  Resumen: {$successCount} sentencias ejecutadas correctamente";
        if ($errorCount > 0) {
            echo ", {$errorCount} con errores";
        }
        echo "\n\n";
    }
    
    // Ejecutar crear.sql
    echo "--- Paso 1: Creando tablas ---\n";
    executeSqlFile($conn, $crearSqlFile, "crear.sql (Definición de tablas)");
    
    // Ejecutar data.sql
    echo "--- Paso 2: Cargando datos iniciales ---\n";
    executeSqlFile($conn, $dataSqlFile, "data.sql (Datos iniciales)");
    
    // Verificar resultado final
    echo "--- Verificación final ---\n";
    $stmt = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    $tables = $stmt->fetchAll();
    
    echo "Tablas en la base de datos: " . count($tables) . "\n";
    foreach ($tables as $table) {
        $tableName = $table['table_name'];
        
        // Contar registros en cada tabla
        try {
            $countStmt = $conn->query("SELECT COUNT(*) as count FROM \"{$tableName}\"");
            $count = $countStmt->fetch()['count'];
            echo "  - {$tableName}: {$count} registros\n";
        } catch (PDOException $e) {
            echo "  - {$tableName}: (error al contar)\n";
        }
    }
    
    echo "\n✓ Configuración de base de datos completada exitosamente\n";
    
} catch (PDOException $e) {
    echo "\n✗ Error de base de datos: " . $e->getMessage() . "\n";
    echo "  Código: " . $e->getCode() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

