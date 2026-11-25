<?php

/** @var \Core\Router $router */

// Página principal de la tienda.
$router->get('/', static function (): void {
    header('Content-Type: text/html; charset=utf-8');

    require __DIR__ . '/../app/Views/home/index.php';
});

// Panel administrativo visual.
$router->get('/admin', static function (): void {
    header('Content-Type: text/html; charset=utf-8');

    require __DIR__ . '/../app/Views/admin/dashboard.php';
});

// Listado visual de productos.
$router->get('/admin/productos', static function (): void {
    header('Content-Type: text/html; charset=utf-8');

    require __DIR__ . '/../app/Views/admin/productos.php';
});

// Listado visual de categorías.
$router->get('/admin/categorias', static function (): void {
    header('Content-Type: text/html; charset=utf-8');

    require __DIR__ . '/../app/Views/admin/categorias.php';
});
