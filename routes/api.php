<?php

use App\Controllers\CategoriaController;
use App\Controllers\ProveedorController;
use App\Controllers\ClienteController;
use App\Controllers\ProductoController;
use App\Controllers\PedidoController;
use App\Controllers\DetallePedidoController;





/** @var PDO $db */
/** @var \Core\Router $router */

// La conexión y el router se crean en public/index.php.
$categoriaController = new CategoriaController($db);

// Listar categorías.
$router->get(
    '/api/categorias',
    [$categoriaController, 'index']
);

// Consultar una categoría por ID.
$router->get(
    '/api/categorias/{id}',
    [$categoriaController, 'show']
);

// Crear una categoría.
$router->post(
    '/api/categorias',
    [$categoriaController, 'store']
);

// Actualizar una categoría.
$router->put(
    '/api/categorias/{id}',
    [$categoriaController, 'update']
);

// Eliminar una categoría.
$router->delete(
    '/api/categorias/{id}',
    [$categoriaController, 'destroy']
);

// ==========================================================
// PROVEEDORES
// ==========================================================

$proveedorController = new ProveedorController($db);

// Listar proveedores.
$router->get(
    '/api/proveedores',
    [$proveedorController, 'index']
);

// Consultar un proveedor por ID.
$router->get(
    '/api/proveedores/{id}',
    [$proveedorController, 'show']
);

// Crear un proveedor.
$router->post(
    '/api/proveedores',
    [$proveedorController, 'store']
);

// Actualizar un proveedor.
$router->put(
    '/api/proveedores/{id}',
    [$proveedorController, 'update']
);

// Eliminar un proveedor.
$router->delete(
    '/api/proveedores/{id}',
    [$proveedorController, 'destroy']
);

// ==========================================================
// CLIENTES
// ==========================================================

$clienteController = new ClienteController($db);

// Listar clientes.
$router->get(
    '/api/clientes',
    [$clienteController, 'index']
);

// Consultar un cliente por ID.
$router->get(
    '/api/clientes/{id}',
    [$clienteController, 'show']
);

// Crear un cliente.
$router->post(
    '/api/clientes',
    [$clienteController, 'store']
);

// Actualizar un cliente.
$router->put(
    '/api/clientes/{id}',
    [$clienteController, 'update']
);

// Eliminar un cliente.
$router->delete(
    '/api/clientes/{id}',
    [$clienteController, 'destroy']
);

// ==========================================================
// PRODUCTOS
// ==========================================================

$productoController = new ProductoController($db);

// Listar productos activos.
$router->get(
    '/api/productos',
    [$productoController, 'index']
);

// Consultar un producto activo por ID.
$router->get(
    '/api/productos/{id}',
    [$productoController, 'show']
);

// Crear un producto.
$router->post(
    '/api/productos',
    [$productoController, 'store']
);

// Actualizar un producto activo.
$router->put(
    '/api/productos/{id}',
    [$productoController, 'update']
);

// Desactivar un producto.
$router->delete(
    '/api/productos/{id}',
    [$productoController, 'destroy']
);


// ==========================================================
// PEDIDOS
// ==========================================================

$pedidoController = new PedidoController($db);

// Listar pedidos.
$router->get(
    '/api/pedidos',
    [$pedidoController, 'index']
);

// Consultar un pedido por ID.
$router->get(
    '/api/pedidos/{id}',
    [$pedidoController, 'show']
);

// Crear un pedido.
$router->post(
    '/api/pedidos',
    [$pedidoController, 'store']
);

// Actualizar un pedido.
$router->put(
    '/api/pedidos/{id}',
    [$pedidoController, 'update']
);

// Eliminar un pedido sin detalles asociados.
$router->delete(
    '/api/pedidos/{id}',
    [$pedidoController, 'destroy']
);

// ==========================================================
// DETALLE DE PEDIDOS
// ==========================================================

$detallePedidoController = new DetallePedidoController($db);

// Listar detalles.
$router->get(
    '/api/detalle-pedidos',
    [$detallePedidoController, 'index']
);

// Consultar un detalle por ID.
$router->get(
    '/api/detalle-pedidos/{id}',
    [$detallePedidoController, 'show']
);

// Crear un detalle.
$router->post(
    '/api/detalle-pedidos',
    [$detallePedidoController, 'store']
);

// Actualizar un detalle.
$router->put(
    '/api/detalle-pedidos/{id}',
    [$detallePedidoController, 'update']
);

// Eliminar un detalle.
$router->delete(
    '/api/detalle-pedidos/{id}',
    [$detallePedidoController, 'destroy']
);
