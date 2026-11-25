<?php

namespace App\Models;

use PDO;

class Categoria
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function obtenerTodos(): array
    {
        $sql = "
            SELECT id_categoria, nombre, descripcion
            FROM categorias
            ORDER BY nombre
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT id_categoria, nombre, descripcion
            FROM categorias
            WHERE id_categoria = :id
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
            INSERT INTO categorias (nombre, descripcion)
            VALUES (:nombre, :descripcion)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion']
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $data): void
    {
        $sql = "
            UPDATE categorias
            SET nombre = :nombre,
                descripcion = :descripcion
            WHERE id_categoria = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':id' => $id
        ]);
    }

    public function eliminar(int $id): int
    {
        $sql = "
            DELETE FROM categorias
            WHERE id_categoria = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount();
    }
}
