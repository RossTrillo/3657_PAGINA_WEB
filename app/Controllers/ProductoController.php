<?php

namespace App\Controllers;

use App\Models\Producto;
use Core\Request;
use Core\Response;
use PDO;
use PDOException;

class ProductoController
{
    private Producto $producto;

    public function __construct(PDO $db)
    {
        $this->producto = new Producto($db);
    }

    public function index(): void
    {
        try {
            $productos = $this->producto->obtenerTodos();

            Response::success(
                $productos,
                'Productos obtenidos correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function show(int $id): void
    {
        $this->validarId($id);

        try {
            $producto = $this->producto->obtenerPorId($id);

            if (!$producto) {
                Response::error('Producto no encontrado o inactivo', 404);
                return;
            }

            Response::success(
                $producto,
                'Producto obtenido correctamente'
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

            $id = $this->producto->crear($data);

            Response::success(
                ['id_producto' => $id],
                'Producto registrado correctamente',
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
            $existente = $this->producto->obtenerPorId($id);

            if (!$existente) {
                Response::error('Producto no encontrado o inactivo', 404);
                return;
            }

            $data = $this->validarDatos(Request::body(), $existente);

            $this->validarRelaciones($data);

            $this->producto->actualizar($id, $data);

            Response::success(
                null,
                'Producto actualizado correctamente'
            );
        } catch (PDOException $error) {
            $this->manejarErrorBaseDatos($error);
        }
    }

    public function destroy(int $id): void
    {
        $this->validarId($id);

        try {
            $filasActualizadas = $this->producto->eliminar($id);

            if ($filasActualizadas === 0) {
                Response::error('Producto no encontrado o inactivo', 404);
                return;
            }

            Response::success(
                null,
                'Producto desactivado correctamente'
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
        $idCategoria = $this->validarEntero(
            $data['id_categoria'] ?? null,
            'id_categoria',
            1
        );

        $idProveedor = $this->validarEntero(
            $data['id_proveedor'] ?? null,
            'id_proveedor',
            1
        );

        $nombre = $this->validarTexto(
            $data['nombre'] ?? null,
            'nombre',
            150,
            true
        );

        $marca = $this->validarTexto(
            $data['marca'] ?? null,
            'marca',
            80,
            true
        );

        $descripcion = $this->validarTexto(
            $data['descripcion'] ?? null,
            'descripción',
            255
        );

        $imagen = $this->validarTexto(
            $data['imagen'] ?? null,
            'imagen',
            255
        );

        $precio = $this->validarPrecio($data['precio'] ?? null);

        // Si no se envía stock, se conserva en la actualización.
        // En una creación, se usa cero.
        $stockRecibido = array_key_exists('stock', $data)
            ? $data['stock']
            : (int) ($existente['stock'] ?? 0);

        $stock = $this->validarEntero(
            $stockRecibido,
            'stock',
            0
        );

        // Si no se envía activo, se conserva el valor existente.
        // En una creación, el producto queda activo por defecto.
        $activoRecibido = array_key_exists('activo', $data)
            ? $data['activo']
            : (int) ($existente['activo'] ?? 1);

        if (!in_array($activoRecibido, [true, false, 1, 0], true)) {
            Response::error(
                'El campo activo debe ser true, false, 1 o 0',
                400
            );
        }

        return [
            'id_categoria' => $idCategoria,
            'id_proveedor' => $idProveedor,
            'nombre' => $nombre,
            'marca' => $marca,
            'descripcion' => $descripcion,
            'imagen' => $imagen,
            'precio' => $precio,
            'stock' => $stock,
            'activo' => (int) $activoRecibido
        ];
    }

    private function validarEntero(
        mixed $valor,
        string $campo,
        int $minimo
    ): int {
        if (
            !is_int($valor) ||
            $valor < $minimo ||
            $valor > 4294967295
        ) {
            Response::error(
                "El campo {$campo} debe ser un entero válido mayor o igual que {$minimo}",
                400
            );
        }

        return $valor;
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

    private function validarPrecio(mixed $valor): string
    {
        if (!is_int($valor) && !is_float($valor) && !is_string($valor)) {
            Response::error('El precio es obligatorio y debe ser numérico', 400);
        }

        $precio = trim((string) $valor);

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $precio)) {
            Response::error(
                'El precio debe ser no negativo y tener como máximo dos decimales',
                400
            );
        }

        if ((float) $precio > 99999999.99) {
            Response::error('El precio supera el máximo permitido', 400);
        }

        return $precio;
    }

    private function validarRelaciones(array $data): void
    {
        if (!$this->producto->existeCategoria($data['id_categoria'])) {
            Response::error('La categoría indicada no existe', 400);
        }

        if (!$this->producto->existeProveedor($data['id_proveedor'])) {
            Response::error('El proveedor indicado no existe', 400);
        }
    }

    private function manejarErrorBaseDatos(PDOException $error): void
    {
        $codigoMysql = $error->errorInfo[1] ?? null;

        if ($codigoMysql === 1452) {
            Response::error(
                'La categoría o el proveedor indicado no existe',
                409
            );
        }

        Response::error(
            'Ocurrió un error al procesar la solicitud',
            500
        );
    }
}
