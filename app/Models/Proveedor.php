<?php

namespace App\Models;

use PDO;

class Proveedor
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function obtenerTodos(): array
    {
        $sql = "
            SELECT id_proveedor, nombre, telefono, correo, direccion
            FROM proveedores
            ORDER BY nombre
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT id_proveedor, nombre, telefono, correo, direccion
            FROM proveedores
            WHERE id_proveedor = :id
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
            INSERT INTO proveedores (
                nombre,
                telefono,
                correo,
                direccion
            )
            VALUES (
                :nombre,
                :telefono,
                :correo,
                :direccion
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':telefono' => $data['telefono'],
            ':correo' => $data['correo'],
            ':direccion' => $data['direccion']
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): void
    {
        $sql = "
            UPDATE proveedores
            SET nombre = :nombre,
                telefono = :telefono,
                correo = :correo,
                direccion = :direccion
            WHERE id_proveedor = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':telefono' => $data['telefono'],
            ':correo' => $data['correo'],
            ':direccion' => $data['direccion'],
            ':id' => $id
        ]);
    }

    public function eliminar(int $id): int
    {
        $sql = "
            DELETE FROM proveedores
            WHERE id_proveedor = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount();
    }
}
