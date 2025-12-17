<?php
require_once __DIR__ . '/Database.php';

use app\config\Database as DatabaseClass;

class Database {
    public static function connect() {
        $db = new DatabaseClass();
        return $db->connect();
    }
}

