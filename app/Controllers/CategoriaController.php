<?php

namespace App\Controllers;

use App\Models\Categoria;
use Core\Request;
use Core\Response;
use PDO;
use PDOException;

class CategoriaController
{
    private Categoria $categoria;

    public function __construct(PDO $db)
    {
        $this->categoria = new Categoria($db);
    }

    public function index(): void
    {
        try {
            $categorias = $this->categoria->obtenerTodos();

            Response::success(
                $categorias,
                'Categorías obtenidas correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function show(int $id): void
    {
        $this->validarId($id);

        try {
            $categoria = $this->categoria->obtenerPorId($id);

            if (!$categoria) {
                Response::error('Categoría no encontrada', 404);
                return;
            }

            Response::success(
                $categoria,
                'Categoría obtenida correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function store(): void
    {
        $data = $this->validarDatos(Request::body());

        try {
            $id = $this->categoria->crear($data);

            Response::success(
                ['id_categoria' => $id],
                'Categoría registrada correctamente',
                201
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function update(int $id): void
    {
        $this->validarId($id);
        $data = $this->validarDatos(Request::body());

        try {
            if (!$this->categoria->obtenerPorId($id)) {
                Response::error('Categoría no encontrada', 404);
                return;
            }

            $this->categoria->actualizar($id, $data);

            Response::success(
                null,
                'Categoría actualizada correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function destroy(int $id): void
    {
        $this->validarId($id);

        try {
            $filasEliminadas = $this->categoria->eliminar($id);

            if ($filasEliminadas === 0) {
                Response::error('Categoría no encontrada', 404);
                return;
            }

            Response::success(
                null,
                'Categoría eliminada correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    private function validarId(int $id): void
    {
        if ($id <= 0) {
            Response::error('El ID debe ser mayor que cero', 400);
        }
    }

    private function validarDatos(array $data): array
    {
        $nombre = $data['nombre'] ?? null;
        $descripcion = $data['descripcion'] ?? null;

        if (!is_string($nombre)) {
            Response::error('El nombre es obligatorio y debe ser texto', 400);
        }

        $nombre = trim($nombre);

        if ($nombre === '') {
            Response::error('El nombre es obligatorio', 400);
        }

        $longitudNombre = preg_match_all('/./us', $nombre);

        if ($longitudNombre === false || $longitudNombre > 80) {
            Response::error(
                'El nombre debe ser texto válido de hasta 80 caracteres',
                400
            );
        }

        if ($descripcion !== null) {
            if (!is_string($descripcion)) {
                Response::error('La descripción debe ser texto o null', 400);
            }

            $descripcion = trim($descripcion);
            $longitudDescripcion = preg_match_all('/./us', $descripcion);

            if ($longitudDescripcion === false || $longitudDescripcion > 255) {
                Response::error(
                    'La descripción debe tener hasta 255 caracteres',
                    400
                );
            }
        }

        return [
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ];
    }

    private function manejarErrorBaseDatos(PDOException $error): void
    {
        $codigoMysql = $error->errorInfo[1] ?? null;

        if ($codigoMysql === 1062) {
            Response::error(
                'Ya existe una categoría con ese nombre',
                409
            );
        }

        if ($codigoMysql === 1451) {
            Response::error(
                'No se puede eliminar la categoría porque tiene productos asociados',
                409
            );
        }

        Response::error(
            'Ocurrió un error al procesar la solicitud',
            500
        );
    }
}
