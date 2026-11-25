# API Mi Tienda

API desarrollada en PHP para administrar la información de una tienda,
utilizando una estructura de modelos, controladores y rutas.

## Módulos

- Categorías
- Proveedores
- Clientes
- Productos
- Pedidos
- Detalle de pedidos

Cada módulo incluye operaciones de consulta, creación, actualización
y eliminación, cuando corresponde.

## Requisitos

- PHP 8 o superior.
- MySQL o MariaDB.
- Composer.
- Un servidor web como Apache en XAMPP.
- Postman para importar y ejecutar las peticiones.

## Instalación

1. Copiar el proyecto en la carpeta `htdocs` de XAMPP.
2. Importar `db_tienda_tecnologica_pa3.sql` desde phpMyAdmin
   en una instalación nueva. El script crea la base de datos
   `db_tienda_tecnologica_pa3`, sus tablas y datos de prueba.
3. Crear un archivo `.env` a partir de `.env.example`.
4. Configurar en `.env` los datos de conexión a la base de datos.
5. Ejecutar `composer install` desde la raíz del proyecto.
6. Iniciar Apache y MySQL en XAMPP.
7. Revisar en `public/index.php` que `$basePath` corresponda al
   nombre y ubicación de la carpeta del proyecto.

## Ejemplo de consulta

Con la ubicación utilizada durante el desarrollo:

GET http://localhost/3657%20Trillo%20Allende%20Rosario/public/api/categorias

La dirección puede variar según la ubicación local del proyecto.

## Pruebas en Postman

La colección de peticiones se encuentra en la carpeta `postman/`.
Puede importarse en Postman para revisar y ejecutar las solicitudes.

**Documentación publicada:** [https://documenter.getpostman.com/view/37384403/2sBYPv4YaT]

Las peticiones de creación, actualización y eliminación modifican datos.
Algunas utilizan IDs de prueba que pueden haber sido eliminados y deben
ajustarse antes de volver a ejecutarlas.

## Consideraciones

- La eliminación de productos es lógica: cambia el campo `activo` a `0`.
- Registrar o eliminar detalles de pedidos no modifica automáticamente
  el stock.
- El archivo `.env` contiene configuración local y no debe publicarse.