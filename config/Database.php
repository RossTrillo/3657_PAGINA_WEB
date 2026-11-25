<?php

namespace Config;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    public function connect(): PDO
    {
        // Obtener la configuración cargada desde el archivo .env.
        $host = $_ENV['DB_HOST'] ?? null;
        $port = $_ENV['DB_PORT'] ?? null;
        $database = $_ENV['DB_DATABASE'] ?? null;
        $username = $_ENV['DB_USERNAME'] ?? null;
        $password = $_ENV['DB_PASSWORD'] ?? null;

        // La contraseña puede estar vacía, pero debe estar definida.
        if (
            $host === null || $host === '' ||
            $port === null || $port === '' ||
            $database === null || $database === '' ||
            $username === null || $username === '' ||
            $password === null
        ) {
            throw new RuntimeException(
                'Falta configurar una o más variables de la base de datos.'
            );
        }

        try {
            // Conexión a MySQL.
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

            $connection = new PDO(
                $dsn,
                $username,
                $password
            );

            $connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            $connection->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );

            return $connection;

        } catch (PDOException $e) {
            // Evitamos mostrar credenciales o detalles internos al cliente.
            throw new PDOException(
                'Error al conectar con la base de datos.'
            );
        }
    }
}
