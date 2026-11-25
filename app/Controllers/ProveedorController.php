<?php

namespace App\Controllers;

use App\Models\Proveedor;
use Core\Request;
use Core\Response;
use PDO;
use PDOException;

class ProveedorController
{
    private Proveedor $proveedor;

    public function __construct(PDO $db)
    {
        $this->proveedor = new Proveedor($db);
    }

    public function index(): void
    {
        try {
            $proveedores = $this->proveedor->obtenerTodos();

            Response::success(
                $proveedores,
                'Proveedores obtenidos correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function show(int $id): void
    {
        $this->validarId($id);

        try {
            $proveedor = $this->proveedor->obtenerPorId($id);

            if (!$proveedor) {
                Response::error('Proveedor no encontrado', 404);
                return;
            }

            Response::success(
                $proveedor,
                'Proveedor obtenido correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function store(): void
    {
        $data = $this->validarDatos(Request::body());

        try {
            $id = $this->proveedor->crear($data);

            Response::success(
                ['id_proveedor' => $id],
                'Proveedor registrado correctamente',
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
            if (!$this->proveedor->obtenerPorId($id)) {
                Response::error('Proveedor no encontrado', 404);
                return;
            }

            $this->proveedor->actualizar($id, $data);

            Response::success(
                null,
                'Proveedor actualizado correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function destroy(int $id): void
    {
        $this->validarId($id);

        try {
            $filasEliminadas = $this->proveedor->eliminar($id);

            if ($filasEliminadas === 0) {
                Response::error('Proveedor no encontrado', 404);
                return;
            }

            Response::success(
                null,
                'Proveedor eliminado correctamente'
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
        $nombre = $this->validarTexto(
            $data['nombre'] ?? null,
            'nombre',
            120,
            true
        );

        $telefono = $this->validarTexto(
            $data['telefono'] ?? null,
            'teléfono',
            20
        );

        $correo = $this->validarTexto(
            $data['correo'] ?? null,
            'correo',
            120
        );

        $direccion = $this->validarTexto(
            $data['direccion'] ?? null,
            'dirección',
            200
        );

        if ($correo !== null && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            Response::error('El correo no tiene un formato válido', 400);
        }

        return [
            'nombre' => $nombre,
            'telefono' => $telefono,
            'correo' => $correo,
            'direccion' => $direccion
        ];
    }

    private function validarTexto(
        mixed $valor,
        string $campo,
        int $longitudMaxima,
        bool $obligatorio = false
    ): ?string {
        if ($valor === null) {
            if ($obligatorio) {
                Response::error("El campo {$campo} es obligatorio", 400);
            }

            return null;
        }

        if (!is_string($valor)) {
            Response::error("El campo {$campo} debe ser texto", 400);
        }

        $valor = trim($valor);

        if ($valor === '') {
            if ($obligatorio) {
                Response::error("El campo {$campo} es obligatorio", 400);
            }

            return null;
        }

        $longitud = preg_match_all('/./us', $valor);

        if ($longitud === false || $longitud > $longitudMaxima) {
            Response::error(
                "El campo {$campo} debe tener hasta {$longitudMaxima} caracteres",
                400
            );
        }

        return $valor;
    }

    private function manejarErrorBaseDatos(PDOException $error): void
    {
        $codigoMysql = $error->errorInfo[1] ?? null;

        if ($codigoMysql === 1062) {
            Response::error(
                'Ya existe un proveedor con ese correo',
                409
            );
        }

        if ($codigoMysql === 1451) {
            Response::error(
                'No se puede eliminar el proveedor porque tiene productos asociados',
                409
            );
        }

        Response::error(
            'Ocurrió un error al procesar la solicitud',
            500
        );
    }
}
