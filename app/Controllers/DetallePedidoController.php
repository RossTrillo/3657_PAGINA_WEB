<?php

namespace App\Controllers;

use App\Models\DetallePedido;
use Core\Request;
use Core\Response;
use PDO;
use PDOException;

class DetallePedidoController
{
    private DetallePedido $detalle;

    public function __construct(PDO $db)
    {
        $this->detalle = new DetallePedido($db);
    }

    public function index(): void
    {
        try {
            $detalles = $this->detalle->obtenerTodos();

            Response::success(
                $detalles,
                'Detalles de pedidos obtenidos correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function show(int $id): void
    {
        $this->validarId($id);

        try {
            $detalle = $this->detalle->obtenerPorId($id);

            if (!$detalle) {
                Response::error('Detalle de pedido no encontrado', 404);
                return;
            }

            Response::success(
                $detalle,
                'Detalle de pedido obtenido correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function store(): void
    {
        $data = $this->validarDatos(Request::body());

        try {
            $this->validarRelaciones($data);

            $id = $this->detalle->crear($data);

            Response::success(
                ['id_detalle' => $id],
                'Detalle de pedido registrado correctamente',
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
            if (!$this->detalle->obtenerPorId($id)) {
                Response::error('Detalle de pedido no encontrado', 404);
                return;
            }

            $this->validarRelaciones($data);

            $this->detalle->actualizar($id, $data);

            Response::success(
                null,
                'Detalle de pedido actualizado correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function destroy(int $id): void
    {
        $this->validarId($id);

        try {
            $filasEliminadas = $this->detalle->eliminar($id);

            if ($filasEliminadas === 0) {
                Response::error('Detalle de pedido no encontrado', 404);
                return;
            }

            Response::success(
                null,
                'Detalle de pedido eliminado correctamente'
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
        $idPedido = $this->validarEntero(
            $data['id_pedido'] ?? null,
            'id_pedido'
        );

        $idProducto = $this->validarEntero(
            $data['id_producto'] ?? null,
            'id_producto'
        );

        $cantidad = $this->validarEntero(
            $data['cantidad'] ?? null,
            'cantidad'
        );

        $precioUnitario = $this->validarPrecio(
            $data['precio_unitario'] ?? null
        );

        return [
            'id_pedido' => $idPedido,
            'id_producto' => $idProducto,
            'cantidad' => $cantidad,
            'precio_unitario' => $precioUnitario
        ];
    }

    private function validarEntero(mixed $valor, string $campo): int
    {
        if (
            !is_int($valor) ||
            $valor < 1 ||
            $valor > 4294967295
        ) {
            Response::error(
                "El campo {$campo} debe ser un entero positivo válido",
                400
            );
        }

        return $valor;
    }

    private function validarPrecio(mixed $valor): string
    {
        if (!is_int($valor) && !is_float($valor) && !is_string($valor)) {
            Response::error(
                'El precio unitario es obligatorio y debe ser numérico',
                400
            );
        }

        $precio = trim((string) $valor);

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $precio)) {
            Response::error(
                'El precio unitario debe ser no negativo y tener como máximo dos decimales',
                400
            );
        }

        if ((float) $precio > 99999999.99) {
            Response::error(
                'El precio unitario supera el máximo permitido',
                400
            );
        }

        return $precio;
    }

    private function validarRelaciones(array $data): void
    {
        if (!$this->detalle->existePedido($data['id_pedido'])) {
            Response::error('El pedido indicado no existe', 400);
        }

        if (!$this->detalle->existeProductoActivo($data['id_producto'])) {
            Response::error(
                'El producto indicado no existe o está inactivo',
                400
            );
        }
    }

    private function manejarErrorBaseDatos(PDOException $error): void
    {
        $codigoMysql = $error->errorInfo[1] ?? null;

        if ($codigoMysql === 1062) {
            Response::error(
                'El producto ya está registrado en ese pedido',
                409
            );
        }

        if ($codigoMysql === 1452) {
            Response::error(
                'El pedido o el producto indicado no existe',
                409
            );
        }

        Response::error(
            'Ocurrió un error al procesar la solicitud',
            500
        );
    }
}
