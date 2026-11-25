<?php

// Carga el autoloader generado por Composer.
require_once __DIR__ . '/../vendor/autoload.php';

// Carga las variables del archivo .env ubicado en la raíz.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
