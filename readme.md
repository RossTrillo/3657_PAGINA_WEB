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

**Documentación publicada:**  
<https://documenter.getpostman.com/view/37384403/2sBYPv4YaT>

Las peticiones de creación, actualización y eliminación modifican
datos. Algunas utilizan IDs de prueba que pueden haber sido eliminados
y deben ajustarse antes de volver a ejecutarlas.

## Avance adicional: interfaz web

Como avance adicional, el proyecto incorpora:

- Una página principal que consulta los productos activos desde la API.
- Una vista de detalle de productos.
- Un panel visual con vistas de consulta para productos y categorías.
- Búsqueda en el navegador sobre los datos cargados en esas vistas.

Este frontend es un avance adicional a las pruebas de la PA4.

### Acceso a la interfaz

Con Apache y MySQL iniciados, y usando la ubicación del proyecto
durante el desarrollo:

- Tienda: `http://localhost/3657%20Trillo%20Allende%20Rosario/public/`
- Panel visual: `http://localhost/3657%20Trillo%20Allende%20Rosario/public/admin`

Las direcciones pueden variar según la carpeta donde se instale
el proyecto y la configuración de `$basePath`.

El panel es una demostración local: todavía no cuenta con inicio
de sesión ni control de acceso. Sus vistas son de consulta, pero las
API CRUD son independientes de ellas y no deben considerarse protegidas
por el panel.

El carrito y el inicio de sesión aún no están implementados como
funcionalidades.

## Consideraciones

- La eliminación de productos es lógica: cambia el campo `activo` a `0`.
- Registrar o eliminar detalles de pedidos no modifica automáticamente
  el stock.
- El archivo `.env` contiene configuración local y no debe publicarse.
