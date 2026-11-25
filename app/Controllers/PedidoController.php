<?php

namespace App\Controllers;

use App\Models\Pedido;
use Core\Request;
use Core\Response;
use PDO;
use PDOException;

class PedidoController
{
    private Pedido $pedido;

    public function __construct(PDO $db)
    {
        $this->pedido = new Pedido($db);
    }

    public function index(): void
    {
        try {
            $pedidos = $this->pedido->obtenerTodos();

            Response::success(
                $pedidos,
                'Pedidos obtenidos correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function show(int $id): void
    {
        $this->validarId($id);

        try {
            $pedido = $this->pedido->obtenerPorId($id);

            if (!$pedido) {
                Response::error('Pedido no encontrado', 404);
                return;
            }

            Response::success(
                $pedido,
                'Pedido obtenido correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function store(): void
    {
        $data = $this->validarDatos(Request::body());

        try {
            $this->validarCliente($data['id_cliente']);

            $id = $this->pedido->crear($data);

            Response::success(
                ['id_pedido' => $id],
                'Pedido registrado correctamente',
                201
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function update(int $id): void
    {
        $this->validarId($id);

        try {
            $existente = $this->pedido->obtenerPorId($id);

            if (!$existente) {
                Response::error('Pedido no encontrado', 404);
                return;
            }

            $data = $this->validarDatos(Request::body(), $existente);

            $this->validarCliente($data['id_cliente']);

            $this->pedido->actualizar($id, $data);

            Response::success(
                null,
                'Pedido actualizado correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function destroy(int $id): void
    {
        $this->validarId($id);

        try {
            $filasEliminadas = $this->pedido->eliminar($id);

            if ($filasEliminadas === 0) {
                Response::error('Pedido no encontrado', 404);
                return;
            }

            Response::success(
                null,
                'Pedido eliminado correctamente'
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

    private function validarDatos(array $data, ?array $existente = null): array
    {
        $idCliente = $data['id_cliente'] ?? null;

        if (
            !is_int($idCliente) ||
            $idCliente < 1 ||
            $idCliente > 4294967295
        ) {
            Response::error(
                'El id_cliente debe ser un número entero positivo válido',
                400
            );
        }

        $direccion = $data['direccion_entrega'] ?? null;

        if (!is_string($direccion)) {
            Response::error(
                'La dirección de entrega es obligatoria y debe ser texto',
                400
            );
        }

        $direccion = trim($direccion);

        if ($direccion === '') {
            Response::error(
                'La dirección de entrega es obligatoria',
                400
            );
        }

        $longitudDireccion = preg_match_all('/./us', $direccion);

        if ($longitudDireccion === false || $longitudDireccion > 200) {
            Response::error(
                'La dirección de entrega debe tener hasta 200 caracteres',
                400
            );
        }

        // En una creación se usa PENDIENTE por defecto.
        // En una actualización se conserva el estado si no se envía.
        $estado = array_key_exists('estado', $data)
            ? $data['estado']
            : ($existente['estado'] ?? 'PENDIENTE');

        $estadosPermitidos = [
            'PENDIENTE',
            'PAGADO',
            'ENVIADO',
            'ENTREGADO',
            'CANCELADO'
        ];

        if (!is_string($estado) || !in_array($estado, $estadosPermitidos, true)) {
            Response::error(
                'El estado no es válido',
                400
            );
        }

        return [
            'id_cliente' => $idCliente,
            'estado' => $estado,
            'direccion_entrega' => $direccion
        ];
    }

    private function validarCliente(int $idCliente): void
    {
        if (!$this->pedido->existeCliente($idCliente)) {
            Response::error('El cliente indicado no existe', 400);
        }
    }

    private function manejarErrorBaseDatos(PDOException $error): void
    {
        $codigoMysql = $error->errorInfo[1] ?? null;

        if ($codigoMysql === 1452) {
            Response::error(
                'El cliente indicado no existe',
                409
            );
        }

        if ($codigoMysql === 1451) {
            Response::error(
                'No se puede eliminar el pedido porque tiene detalles asociados',
                409
            );
        }

        Response::error(
            'Ocurrió un error al procesar la solicitud',
            500
        );
    }
}
