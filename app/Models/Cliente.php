<?php

namespace App\Models;

use PDO;

class Cliente
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
                id_cliente,
                nombres,
                apellidos,
                correo,
                telefono,
                direccion,
                fecha_registro
            FROM clientes
            ORDER BY apellidos, nombres
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT
                id_cliente,
                nombres,
                apellidos,
                correo,
                telefono,
                direccion,
                fecha_registro
            FROM clientes
            WHERE id_cliente = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function crear(array $data): int
    {
        $sql = "
            INSERT INTO clientes (
                nombres,
                apellidos,
                correo,
                telefono,
                direccion
            )
            VALUES (
                :nombres,
                :apellidos,
                :correo,
                :telefono,
                :direccion
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombres' => $data['nombres'],
            ':apellidos' => $data['apellidos'],
            ':correo' => $data['correo'],
            ':telefono' => $data['telefono'],
            ':direccion' => $data['direccion']
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): void
    {
        $sql = "
            UPDATE clientes
            SET nombres = :nombres,
                apellidos = :apellidos,
                correo = :correo,
                telefono = :telefono,
                direccion = :direccion
            WHERE id_cliente = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombres' => $data['nombres'],
            ':apellidos' => $data['apellidos'],
            ':correo' => $data['correo'],
            ':telefono' => $data['telefono'],
            ':direccion' => $data['direccion'],
            ':id' => $id
        ]);
    }

    public function eliminar(int $id): int
    {
        $sql = "
            DELETE FROM clientes
            WHERE id_cliente = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount();
    }
}
