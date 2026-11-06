<?php

namespace App\Database;

use PDO;

class Connection
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function get(): PDO
    {
        if (self::$pdo === null) {

            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $database = getenv('DB_DATABASE');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');

            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

            self::$pdo = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$pdo;
    }
}