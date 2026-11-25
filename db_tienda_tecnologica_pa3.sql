CREATE DATABASE db_tienda_tecnologica_pa3
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE db_tienda_tecnologica_pa3;

-- =========================================================
-- 2. CATEGORÍAS
-- Clasifica los dispositivos y accesorios de la tienda.
-- =========================================================

CREATE TABLE categorias (
    id_categoria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
);

-- =========================================================
-- 3. PROVEEDORES
-- Registra las empresas proveedoras de los productos.
-- =========================================================

CREATE TABLE proveedores (
    id_proveedor INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(120) UNIQUE,
    direccion VARCHAR(200)
);

-- =========================================================
-- 4. CLIENTES
-- Registra los datos básicos de los compradores.
-- =========================================================

CREATE TABLE clientes (
    id_cliente INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    direccion VARCHAR(200),
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- =========================================================
-- 5. PRODUCTOS
-- Cada producto pertenece a una categoría y tiene
-- un proveedor principal.
-- =========================================================

CREATE TABLE productos (
    id_producto INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_categoria INT UNSIGNED NOT NULL,
    id_proveedor INT UNSIGNED NOT NULL,

    nombre VARCHAR(150) NOT NULL,
    marca VARCHAR(80) NOT NULL,
    descripcion VARCHAR(255),
    imagen VARCHAR(255),
    precio DECIMAL(10, 2) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    activo BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT fk_producto_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES categorias (id_categoria)
        ON DELETE RESTRICT,

    CONSTRAINT fk_producto_proveedor
        FOREIGN KEY (id_proveedor)
        REFERENCES proveedores (id_proveedor)
        ON DELETE RESTRICT
);

-- =========================================================
-- 6. PEDIDOS
-- Cada pedido pertenece a un cliente.
-- =========================================================

CREATE TABLE pedidos (
    id_pedido INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT UNSIGNED NOT NULL,

    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    estado ENUM(
        'PENDIENTE',
        'PAGADO',
        'ENVIADO',
        'ENTREGADO',
        'CANCELADO'
    ) NOT NULL DEFAULT 'PENDIENTE',

    direccion_entrega VARCHAR(200) NOT NULL,

    CONSTRAINT fk_pedido_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES clientes (id_cliente)
        ON DELETE RESTRICT
);


-- =========================================================
-- 7. DETALLE DE PEDIDOS
-- Registra los productos incluidos en cada pedido.
-- =========================================================

CREATE TABLE detalle_pedidos (
    id_detalle INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_pedido INT UNSIGNED NOT NULL,
    id_producto INT UNSIGNED NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,

    CONSTRAINT fk_detalle_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES pedidos (id_pedido)
        ON DELETE RESTRICT,

    CONSTRAINT fk_detalle_producto
        FOREIGN KEY (id_producto)
        REFERENCES productos (id_producto)
        ON DELETE RESTRICT,

    CONSTRAINT uq_pedido_producto
        UNIQUE (id_pedido, id_producto)
);

-- =========================================================
-- 8. ÍNDICES
-- Apoyan las consultas por nombre y precio de productos.
-- =========================================================

CREATE INDEX idx_productos_nombre
ON productos (nombre);

CREATE INDEX idx_productos_precio
ON productos (precio);

-- =========================================================
-- 9. DATOS DE PRUEBA
-- =========================================================


-- ---------------------------------------------------------
-- CATEGORÍAS: 5 registros
-- ---------------------------------------------------------

INSERT INTO categorias (
    id_categoria,
    nombre,
    descripcion
)
VALUES
    (1, 'Celulares', 'Teléfonos inteligentes'),
    (2, 'Laptops', 'Computadoras portátiles'),
    (3, 'Tablets', 'Tabletas electrónicas'),
    (4, 'Computadoras', 'Computadoras de escritorio'),
    (5, 'Accesorios', 'Accesorios para dispositivos tecnológicos');

-- ---------------------------------------------------------
-- PROVEEDORES: 3 registros
-- ---------------------------------------------------------

INSERT INTO proveedores (
    id_proveedor,
    nombre,
    telefono,
    correo,
    direccion
)
VALUES
    (
        1,
        'Distribuidora Digital Andina',
        '987654321',
        'digitalandina.2471@gmail.com',
        'Av. Argentina 1250, Lima'
    ),
    (
        2,
        'Tecnología y Equipos del Perú',
        '976543210',
        'equiposperu.6824@gmail.com',
        'Av. Arequipa 840, Lima'
    ),
    (
        3,
        'Importaciones Tecno Sur',
        '965432109',
        'tecnosur.3956@gmail.com',
        'Jr. Paruro 680, Lima'
    );

-- ---------------------------------------------------------
-- CLIENTES: 3 registros
-- ---------------------------------------------------------

INSERT INTO clientes (
    id_cliente,
    nombres,
    apellidos,
    correo,
    telefono,
    direccion
)
VALUES
    (
        1,
        'Valeria',
        'Quispe Ramos',
        'valeriaquispe.2176@gmail.com',
        '987123456',
        'Av. Los Próceres 450, Lima'
    ),
    (
        2,
        'Diego',
        'Mendoza Torres',
        'diegomendoza.4382@gmail.com',
        '976234567',
        'Jr. Las Palmeras 325, Lima'
    ),
    (
        3,
        'Camila',
        'Salazar Rojas',
        'camilasalazar.7591@gmail.com',
        '965345678',
        'Calle Los Cedros 180, Lima'
    );

-- ---------------------------------------------------------
-- PRODUCTOS: 9 registros
-- ---------------------------------------------------------

INSERT INTO productos (
    id_producto,
    id_categoria,
    id_proveedor,
    nombre,
    marca,
    descripcion,
    imagen,
    precio,
    stock,
    activo
)
VALUES
    (
        1,
        1,
        1,
        'Samsung Galaxy S24 Ultra',
        'Samsung',
        'Celular con 12 GB de RAM y 256 GB de almacenamiento',
        'https://media.falabella.com/falabellaPE/155430867_01/public',
        4299.00,
        8,
        TRUE
    ),
    (
        2,
        1,
        2,
        'iPhone 15',
        'Apple',
        'Celular con 128 GB de almacenamiento',
        'https://media.falabella.com/falabellaPE/20039955_01/w=1500,h=1500,fit=cover',
        2999.00,
        10,
        TRUE
    ),
    (
        3,
        2,
        1,
        'ASUS Vivobook 15',
        'ASUS',
        'Laptop de 15.6 pulgadas con SSD de 512 GB',
        'https://media.falabella.com/tottusPE/43674267_1/w=1500,h=1500,fit=cover',
        2499.00,
        6,
        TRUE
    ),
    (
        4,
        2,
        3,
        'MacBook Air de 13 pulgadas con chip M3',
        'Apple',
        'Laptop portátil con chip Apple M3',
        'https://media.falabella.com.pe/falabellaPE/159007277_01/width=170,height=170,quality=70,format=webp,fit=pad',
        4699.00,
        5,
        TRUE
    ),
    (
        5,
        3,
        2,
        'iPad Air de 11 pulgadas con chip M3',
        'Apple',
        'Tablet con chip Apple M3 y 128 GB de almacenamiento',
        'https://media.falabella.com.pe/falabellaPE/130691430_01/width=170,height=170,quality=70,format=webp,fit=pad',
        2999.00,
        7,
        TRUE
    ),
    (
        6,
        4,
        3,
        'Apple Mac mini con chip M4',
        'Apple',
        'Computadora de escritorio con 16 GB de memoria y SSD de 256 GB',
        'https://media.falabella.com.pe/falabellaPE/158814310_01/width=170,height=170,quality=70,format=webp,fit=pad',
        3299.00,
        4,
        TRUE
    ),
    (
        7,
        5,
        1,
        'Cargador USB-C de 25 W',
        'Samsung',
        'Cargador de pared para dispositivos compatibles con USB-C',
        'https://media.falabella.com.pe/falabellaPE/143981307_01/width=170,height=170,quality=70,format=webp,fit=pad',
        89.00,
        20,
        TRUE
    ),
    (
        8,
        5,
        2,
        'Cable de carga USB-C',
        'Apple',
        'Cable USB-C para carga y transferencia de datos',
        'https://media.falabella.com.pe/falabellaPE/152562072_01/width=170,height=170,quality=70,format=webp,fit=pad',
        119.00,
        15,
        TRUE
    ),
    (
        9,
        5,
        3,
        'Audífonos Galaxy Buds2 Pro',
        'Samsung',
        'Audífonos inalámbricos con estuche de carga',
        'https://media.falabella.com.pe/falabellaPE/152881513_01/width=170,height=170,quality=70,format=webp,fit=pad',
        659.00,
        10,
        TRUE
    );
-- ---------------------------------------------------------
-- PEDIDOS: 3 registros
-- ---------------------------------------------------------

INSERT INTO pedidos (
    id_pedido,
    id_cliente,
    estado,
    direccion_entrega
)
VALUES
    (
        1,
        1,
        'PENDIENTE',
        'Av. Los Próceres 450, Lima'
    ),
    (
        2,
        2,
        'PAGADO',
        'Jr. Las Palmeras 325, Lima'
    ),
    (
        3,
        3,
        'ENTREGADO',
        'Calle Los Cedros 180, Lima'
    );

-- ---------------------------------------------------------
-- DETALLE DE PEDIDOS: 4 registros
-- ---------------------------------------------------------
INSERT INTO detalle_pedidos (
    id_detalle,
    id_pedido,
    id_producto,
    cantidad,
    precio_unitario
)
VALUES
    (1, 1, 1, 1, 4299.00),
    (2, 1, 5, 1, 2999.00),
    (3, 2, 3, 1, 2499.00),
    (4, 3, 2, 2, 2999.00);

    -- =========================================================
-- 10. PROCEDIMIENTO ALMACENADO
-- Actualiza el stock de un producto existente.
-- No actualiza si el nuevo stock es negativo.
-- =========================================================

CREATE PROCEDURE sp_actualizar_stock(
    IN p_id_producto INT,
    IN p_nuevo_stock INT
)
UPDATE productos
SET stock = p_nuevo_stock
WHERE id_producto = p_id_producto
  AND p_nuevo_stock >= 0;

