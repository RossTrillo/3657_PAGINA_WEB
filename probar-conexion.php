<?php

require_once __DIR__ . '/config/bootstrap.php';

use Config\Database;

try {
    $database = new Database();
    $connection = $database->connect();

    $statement = $connection->query('SELECT DATABASE() AS base_datos');
    $resultado = $statement->fetch();

    echo "Conexión exitosa.\n";
    echo "Base de datos: " . $resultado['base_datos'] . "\n";

} catch (Throwable $error) {
    echo "No se pudo completar la prueba de conexión.\n";
    echo $error->getMessage() . "\n";
    exit(1);
}
