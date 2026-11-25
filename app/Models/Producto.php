<?php

namespace App\Models;

use PDO;

class Producto
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function obtenerTodos(): array
    {
        $sql = "
            SELECT
                p.id_producto,
                p.id_categoria,
                c.nombre AS categoria,
                p.id_proveedor,
                pr.nombre AS proveedor,
                p.nombre,
                p.marca,
                p.descripcion,
                p.imagen,
                p.precio,
                p.stock,
                p.activo
            FROM productos p
            INNER JOIN categorias c
                ON p.id_categoria = c.id_categoria
            INNER JOIN proveedores pr
                ON p.id_proveedor = pr.id_proveedor
            WHERE p.activo = 1
            ORDER BY p.nombre
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT
                p.id_producto,
                p.id_categoria,
                c.nombre AS categoria,
                p.id_proveedor,
                pr.nombre AS proveedor,
                p.nombre,
                p.marca,
                p.descripcion,
                p.imagen,
                p.precio,
                p.stock,
                p.activo
            FROM productos p
            INNER JOIN categorias c
                ON p.id_categoria = c.id_categoria
            INNER JOIN proveedores pr
                ON p.id_proveedor = pr.id_proveedor
            WHERE p.id_producto = :id
              AND p.activo = 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function existeCategoria(int $id): bool
    {
        $sql = "
            SELECT 1
            FROM categorias
            WHERE id_categoria = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetchColumn() !== false;
    }

    public function existeProveedor(int $id): bool
    {
        $sql = "
            SELECT 1
            FROM proveedores
            WHERE id_proveedor = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetchColumn() !== false;
    }

    public function crear(array $data): int
    {
        $sql = "
            INSERT INTO productos (
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
            VALUES (
                :id_categoria,
                :id_proveedor,
                :nombre,
                :marca,
                :descripcion,
                :imagen,
                :precio,
                :stock,
                :activo
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_categoria' => $data['id_categoria'],
            ':id_proveedor' => $data['id_proveedor'],
            ':nombre' => $data['nombre'],
            ':marca' => $data['marca'],
            ':descripcion' => $data['descripcion'],
            ':imagen' => $data['imagen'],
            ':precio' => $data['precio'],
            ':stock' => $data['stock'],
            ':activo' => $data['activo']
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): void
    {
        $sql = "
            UPDATE productos
            SET id_categoria = :id_categoria,
                id_proveedor = :id_proveedor,
                nombre = :nombre,
                marca = :marca,
                descripcion = :descripcion,
                imagen = :imagen,
                precio = :precio,
                stock = :stock,
                activo = :activo
            WHERE id_producto = :id
              AND activo = 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_categoria' => $data['id_categoria'],
            ':id_proveedor' => $data['id_proveedor'],
            ':nombre' => $data['nombre'],
            ':marca' => $data['marca'],
            ':descripcion' => $data['descripcion'],
            ':imagen' => $data['imagen'],
            ':precio' => $data['precio'],
            ':stock' => $data['stock'],
            ':activo' => $data['activo'],
            ':id' => $id
        ]);
    }

    public function eliminar(int $id): int
    {
        $sql = "
            UPDATE productos
            SET activo = 0
            WHERE id_producto = :id
              AND activo = 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount();
    }
}
