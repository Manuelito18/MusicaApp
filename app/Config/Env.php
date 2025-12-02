<?php

namespace App\Config;

class Env
{
  /**
   * Carga las variables de entorno desde el archivo .env
   * 
   * @param string $path Ruta al archivo .env
   * @return void
   */
  public static function load(string $path): void
  {
    if (!file_exists($path)) {
      throw new \RuntimeException("El archivo .env no existe en: {$path}");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      // Ignorar comentarios (líneas que empiezan con #)
      $trimmedLine = trim($line);
      if (empty($trimmedLine) || strpos($trimmedLine, '#') === 0) {
        continue;
      }

      // Parsear la línea
      if (strpos($line, '=') !== false) {
        list($name, $value) = explode('=', $line, 2);

        $name = trim($name);
        $value = trim($value);

        // Remover comentarios inline (después del valor)
        // Buscar # que no esté dentro de comillas
        $commentPos = strpos($value, '#');
        if ($commentPos !== false) {
          // Verificar si el # está dentro de comillas
          $beforeComment = substr($value, 0, $commentPos);
          $quoteCount = substr_count($beforeComment, '"') + substr_count($beforeComment, "'");

          // Si hay un número par de comillas antes del #, entonces el # es un comentario
          if ($quoteCount % 2 === 0) {
            $value = trim(substr($value, 0, $commentPos));
          }
        }

        // Remover comillas si existen
        $value = self::removeQuotes($value);

        // Establecer la variable de entorno solo si el nombre no está vacío
        if (!empty($name) && !array_key_exists($name, $_ENV)) {
          putenv("{$name}={$value}");
          $_ENV[$name] = $value;
          $_SERVER[$name] = $value;
        }
      }
    }
  }

  /**
   * Obtiene una variable de entorno
   * 
   * @param string $key Nombre de la variable
   * @param mixed $default Valor por defecto si no existe
   * @return mixed
   */
  public static function get(string $key, $default = null)
  {
    // Intentar obtener de $_ENV primero
    if (isset($_ENV[$key])) {
      return self::parseValue($_ENV[$key]);
    }

    // Intentar obtener de $_SERVER
    if (isset($_SERVER[$key])) {
      return self::parseValue($_SERVER[$key]);
    }

    // Intentar obtener con getenv()
    $value = getenv($key);
    if ($value !== false) {
      return self::parseValue($value);
    }

    return $default;
  }

  /**
   * Remueve comillas de un valor
   * 
   * @param string $value
   * @return string
   */
  private static function removeQuotes(string $value): string
  {
    $value = trim($value);

    // Remover comillas dobles
    if (strlen($value) > 1 && $value[0] === '"' && $value[strlen($value) - 1] === '"') {
      return substr($value, 1, -1);
    }

    // Remover comillas simples
    if (strlen($value) > 1 && $value[0] === "'" && $value[strlen($value) - 1] === "'") {
      return substr($value, 1, -1);
    }

    return $value;
  }

  /**
   * Parsea valores especiales (true, false, null)
   * 
   * @param string $value
   * @return mixed
   */
  private static function parseValue(string $value)
  {
    $lower = strtolower($value);

    if ($lower === 'true') {
      return true;
    }

    if ($lower === 'false') {
      return false;
    }

    if ($lower === 'null') {
      return null;
    }

    return $value;
  }
}
