<?php

namespace App\Controllers;

use App\Models\Cliente;
use Core\Request;
use Core\Response;
use PDO;
use PDOException;

class ClienteController
{
    private Cliente $cliente;

    public function __construct(PDO $db)
    {
        $this->cliente = new Cliente($db);
    }

    public function index(): void
    {
        try {
            $clientes = $this->cliente->obtenerTodos();

            Response::success(
                $clientes,
                'Clientes obtenidos correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function show(int $id): void
    {
        $this->validarId($id);

        try {
            $cliente = $this->cliente->obtenerPorId($id);

            if (!$cliente) {
                Response::error('Cliente no encontrado', 404);
                return;
            }

            Response::success(
                $cliente,
                'Cliente obtenido correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function store(): void
    {
        $data = $this->validarDatos(Request::body());

        try {
            $id = $this->cliente->crear($data);

            Response::success(
                ['id_cliente' => $id],
                'Cliente registrado correctamente',
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
            if (!$this->cliente->obtenerPorId($id)) {
                Response::error('Cliente no encontrado', 404);
                return;
            }

            $this->cliente->actualizar($id, $data);

            Response::success(
                null,
                'Cliente actualizado correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function destroy(int $id): void
    {
        $this->validarId($id);

        try {
            $filasEliminadas = $this->cliente->eliminar($id);

            if ($filasEliminadas === 0) {
                Response::error('Cliente no encontrado', 404);
                return;
            }

            Response::success(
                null,
                'Cliente eliminado correctamente'
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
        $nombres = $this->validarTexto(
            $data['nombres'] ?? null,
            'nombres',
            100,
            true
        );

        $apellidos = $this->validarTexto(
            $data['apellidos'] ?? null,
            'apellidos',
            100,
            true
        );

        $correo = $this->validarTexto(
            $data['correo'] ?? null,
            'correo',
            120,
            true
        );

        $telefono = $this->validarTexto(
            $data['telefono'] ?? null,
            'teléfono',
            20
        );

        $direccion = $this->validarTexto(
            $data['direccion'] ?? null,
            'dirección',
            200
        );

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            Response::error('El correo no tiene un formato válido', 400);
        }

        return [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'correo' => $correo,
            'telefono' => $telefono,
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
                'Ya existe un cliente con ese correo',
                409
            );
        }

        if ($codigoMysql === 1451) {
            Response::error(
                'No se puede eliminar el cliente porque tiene pedidos asociados',
                409
            );
        }

        Response::error(
            'Ocurrió un error al procesar la solicitud',
            500
        );
    }
}
