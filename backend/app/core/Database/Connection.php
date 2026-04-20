<?php

declare(strict_types=1);

namespace App\Core\Database;

use PDO;
use PDOException;
use RuntimeException;

final class Connection
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance instanceof PDO) {
            return self::$instance;
        }

        $host = env_string('DB_HOST');
        $port = env_string('DB_PORT', '3306');
        $database = env_string('DB_NAME');
        $username = env_string('DB_USER');
        $password = env_string('DB_PASSWORD', '');
        $charset = env_string('DB_CHARSET', 'utf8mb4');

        if ($host === null || $database === null || $username === null) {
            throw new RuntimeException('Configuración de base de datos incompleta en variables de entorno.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $host,
            $port,
            $database,
            $charset
        );

        try {
            self::$instance = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('No fue posible establecer conexión con la base de datos.', 0, $e);
        }

        return self::$instance;
    }
}
