<?php

namespace App\Models;

use PDO;

class DetallePedido
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
                d.id_detalle,
                d.id_pedido,
                d.id_producto,
                p.nombre AS producto,
                d.cantidad,
                d.precio_unitario
            FROM detalle_pedidos d
            INNER JOIN productos p
                ON d.id_producto = p.id_producto
            ORDER BY d.id_detalle
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT
                d.id_detalle,
                d.id_pedido,
                d.id_producto,
                p.nombre AS producto,
                d.cantidad,
                d.precio_unitario
            FROM detalle_pedidos d
            INNER JOIN productos p
                ON d.id_producto = p.id_producto
            WHERE d.id_detalle = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function existePedido(int $id): bool
    {
        $sql = "
            SELECT 1
            FROM pedidos
            WHERE id_pedido = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetchColumn() !== false;
    }

    public function existeProductoActivo(int $id): bool
    {
        $sql = "
            SELECT 1
            FROM productos
            WHERE id_producto = :id
              AND activo = 1
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
            INSERT INTO detalle_pedidos (
                id_pedido,
                id_producto,
                cantidad,
                precio_unitario
            )
            VALUES (
                :id_pedido,
                :id_producto,
                :cantidad,
                :precio_unitario
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pedido' => $data['id_pedido'],
            ':id_producto' => $data['id_producto'],
            ':cantidad' => $data['cantidad'],
            ':precio_unitario' => $data['precio_unitario']
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): void
    {
        $sql = "
            UPDATE detalle_pedidos
            SET id_pedido = :id_pedido,
                id_producto = :id_producto,
                cantidad = :cantidad,
                precio_unitario = :precio_unitario
            WHERE id_detalle = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pedido' => $data['id_pedido'],
            ':id_producto' => $data['id_producto'],
            ':cantidad' => $data['cantidad'],
            ':precio_unitario' => $data['precio_unitario'],
            ':id' => $id
        ]);
    }

    public function eliminar(int $id): int
    {
        $sql = "
            DELETE FROM detalle_pedidos
            WHERE id_detalle = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount();
    }
}
