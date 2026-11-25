<?php

namespace App\Models;

use PDO;

class Pedido
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
                p.id_pedido,
                p.id_cliente,
                c.nombres AS cliente_nombres,
                c.apellidos AS cliente_apellidos,
                p.fecha_pedido,
                p.estado,
                p.direccion_entrega
            FROM pedidos p
            INNER JOIN clientes c
                ON p.id_cliente = c.id_cliente
            ORDER BY p.id_pedido DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT
                p.id_pedido,
                p.id_cliente,
                c.nombres AS cliente_nombres,
                c.apellidos AS cliente_apellidos,
                p.fecha_pedido,
                p.estado,
                p.direccion_entrega
            FROM pedidos p
            INNER JOIN clientes c
                ON p.id_cliente = c.id_cliente
            WHERE p.id_pedido = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function existeCliente(int $id): bool
    {
        $sql = "
            SELECT 1
            FROM clientes
            WHERE id_cliente = :id
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
            INSERT INTO pedidos (
                id_cliente,
                estado,
                direccion_entrega
            )
            VALUES (
                :id_cliente,
                :estado,
                :direccion_entrega
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_cliente' => $data['id_cliente'],
            ':estado' => $data['estado'],
            ':direccion_entrega' => $data['direccion_entrega']
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): void
    {
        $sql = "
            UPDATE pedidos
            SET id_cliente = :id_cliente,
                estado = :estado,
                direccion_entrega = :direccion_entrega
            WHERE id_pedido = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_cliente' => $data['id_cliente'],
            ':estado' => $data['estado'],
            ':direccion_entrega' => $data['direccion_entrega'],
            ':id' => $id
        ]);
    }

    public function eliminar(int $id): int
    {
        $sql = "
            DELETE FROM pedidos
            WHERE id_pedido = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount();
    }
}
