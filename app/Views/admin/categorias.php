<?php

$base = htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categorías | Panel de Mi Tienda</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        .panel {
            display: flex;
            min-height: 100vh;
        }

        .barra-lateral {
            width: 240px;
            flex-shrink: 0;
            padding: 24px;
            background: #111827;
            color: #fff;
        }

        .marca {
            margin: 0 0 30px;
            font-size: 20px;
            font-weight: bold;
        }

        .navegacion {
            display: grid;
            gap: 8px;
        }

        .navegacion a,
        .navegacion span {
            display: block;
            padding: 12px;
            border-radius: 8px;
            color: #d1d5db;
            text-decoration: none;
        }

        .navegacion a:hover {
            background: #374151;
        }

        .navegacion .activo {
            background: #2563eb;
            color: #fff;
        }

        .navegacion span {
            opacity: 0.65;
        }

        .contenido {
            flex: 1;
            min-width: 0;
        }

        .cabecera {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 28px;
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
        }

        .cabecera a {
            color: #2563eb;
            text-decoration: none;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 28px;
        }

        .etiqueta {
            color: #2563eb;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        h1 {
            margin: 8px 0;
            font-size: 30px;
        }

        .descripcion {
            color: #6b7280;
        }

        .aviso {
            margin: 24px 0;
            padding: 14px 16px;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 14px;
        }

        .tarjeta {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .controles {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 12px;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .campo-busqueda {
            flex: 1;
            min-width: 220px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font: inherit;
        }

        button {
            padding: 11px 16px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
            font: inherit;
        }

        button:hover {
            background: #1d4ed8;
        }

        button:disabled {
            cursor: wait;
            opacity: 0.65;
        }

        .mensaje {
            min-height: 20px;
            margin: 0;
            padding: 14px 20px;
            color: #6b7280;
            font-size: 14px;
        }

        .tabla-contenedor {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th,
        td {
            padding: 14px 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
        }

        th {
            background: #f9fafb;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .panel {
                display: block;
            }

            .barra-lateral {
                width: 100%;
            }

            .marca {
                margin-bottom: 16px;
            }

            .navegacion {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .cabecera,
            main {
                padding-right: 20px;
                padding-left: 20px;
            }
        }
    </style>
</head>

<body data-api-categorias="<?= $base ?>/api/categorias">

    <div class="panel">

        <!-- Menú lateral -->
        <aside class="barra-lateral">
            <p class="marca">Mi Tienda · Panel</p>

            <nav class="navegacion" aria-label="Menú administrativo">
                <a href="<?= $base ?>/admin">
                    Resumen
                </a>

                <a href="<?= $base ?>/admin/productos">
                    Productos
                </a>

                <a
                    class="activo"
                    href="<?= $base ?>/admin/categorias"
                    aria-current="page"
                >
                    Categorías
                </a>

                <span>Proveedores · Próximamente</span>
                <span>Clientes · Próximamente</span>
                <span>Pedidos · Próximamente</span>
            </nav>
        </aside>

        <div class="contenido">

            <header class="cabecera">
                <strong>Panel administrativo</strong>

                <a href="<?= $base ?>/">
                    Ver tienda
                </a>
            </header>

            <main>
                <p class="etiqueta">Consulta de API</p>

                <h1>Categorías</h1>

                <p class="descripcion">
                    Consulta las categorías registradas en tu tienda.
                </p>

                <div class="aviso">
                    Vista de demostración local y de solo lectura.
                    Todavía no tiene inicio de sesión ni control de acceso.
                </div>

                <section class="tarjeta" aria-label="Listado de categorías">

                    <!-- Búsqueda y actualización -->
                    <div class="controles">
                        <div class="campo-busqueda">
                            <label for="buscar-categoria">
                                Buscar por nombre o descripción
                            </label>

                            <input
                                id="buscar-categoria"
                                type="search"
                                placeholder="Ejemplo: celulares"
                                autocomplete="off"
                            >
                        </div>

                        <button id="actualizar-categorias" type="button">
                            Actualizar
                        </button>
                    </div>

                    <!-- Estado de la consulta -->
                    <p
                        id="mensaje-categorias"
                        class="mensaje"
                        role="status"
                        aria-live="polite"
                    >
                        Preparando consulta...
                    </p>

                    <!-- Tabla -->
                    <div class="tabla-contenedor">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Categoría</th>
                                    <th scope="col">Descripción</th>
                                </tr>
                            </thead>

                            <tbody id="tabla-categorias"></tbody>
                        </table>
                    </div>

                </section>
            </main>

        </div>
    </div>

    <script>
        const apiCategorias = document.body.dataset.apiCategorias;

        const tablaCategorias = document.getElementById("tabla-categorias");
        const mensajeCategorias = document.getElementById("mensaje-categorias");
        const buscarCategoria = document.getElementById("buscar-categoria");
        const actualizarCategorias = document.getElementById("actualizar-categorias");

        let categorias = [];

        function mostrarMensaje(texto) {
            mensajeCategorias.textContent = texto;
        }

        // Agrega una celda usando texto, no HTML.
        function agregarCelda(fila, valor) {
            const celda = document.createElement("td");

            celda.textContent =
                valor === null || valor === undefined || valor === ""
                    ? "—"
                    : String(valor);

            fila.append(celda);
        }

        function renderizarCategorias(lista) {
            tablaCategorias.replaceChildren();

            if (lista.length === 0) {
                mostrarMensaje("No se encontraron categorías.");
                return;
            }

            lista.forEach((categoria) => {
                const fila = document.createElement("tr");

                agregarCelda(fila, categoria.id_categoria);
                agregarCelda(fila, categoria.nombre);
                agregarCelda(fila, categoria.descripcion);

                tablaCategorias.append(fila);
            });

            mostrarMensaje(`${lista.length} categoría(s) mostrada(s).`);
        }

        // Busca sobre las categorías ya obtenidas de la API.
        function aplicarBusqueda() {
            const texto = buscarCategoria.value
                .trim()
                .toLocaleLowerCase("es");

            const filtradas = categorias.filter((categoria) => {
                const nombre = String(categoria.nombre || "")
                    .toLocaleLowerCase("es");

                const descripcion = String(categoria.descripcion || "")
                    .toLocaleLowerCase("es");

                return nombre.includes(texto) || descripcion.includes(texto);
            });

            renderizarCategorias(filtradas);
        }

        async function cargarCategorias() {
            buscarCategoria.disabled = true;
            actualizarCategorias.disabled = true;
            tablaCategorias.replaceChildren();
            mostrarMensaje("Cargando categorías...");

            try {
                const respuesta = await fetch(apiCategorias, {
                    headers: {
                        Accept: "application/json"
                    }
                });

                const resultado = await respuesta.json();

                if (
                    !respuesta.ok ||
                    resultado.success !== true ||
                    !Array.isArray(resultado.data)
                ) {
                    throw new Error(
                        resultado.message || "La respuesta de la API no es válida."
                    );
                }

                categorias = resultado.data;
                aplicarBusqueda();
            } catch (error) {
                categorias = [];

                console.error("Error al consultar categorías:", error);

                mostrarMensaje(
                    "No se pudieron cargar las categorías. Revisa la API, Apache y MySQL."
                );
            } finally {
                buscarCategoria.disabled = false;
                actualizarCategorias.disabled = false;
            }
        }

        buscarCategoria.addEventListener("input", aplicarBusqueda);
        actualizarCategorias.addEventListener("click", cargarCategorias);

        cargarCategorias();
    </script>

</body>
</html>
