<?php

$inicio = htmlspecialchars(BASE_URL . '/', ENT_QUOTES, 'UTF-8');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel administrativo | Mi Tienda</title>

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

        .cabecera strong {
            font-size: 18px;
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
            margin-top: 24px;
            padding: 14px 16px;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 14px;
        }

        .tarjetas {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-top: 24px;
        }

        .tarjeta {
            padding: 22px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .tarjeta h2 {
            margin: 0;
            font-size: 16px;
        }

        .tarjeta strong {
            display: block;
            margin-top: 16px;
            color: #2563eb;
            font-size: 24px;
        }

        .tarjeta p {
            margin-bottom: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .accesos {
            margin-top: 24px;
        }

        .enlace {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
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

            .tarjetas {
                grid-template-columns: 1fr;
            }

            .cabecera,
            main {
                padding-right: 20px;
                padding-left: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="panel">

        <!-- Menú lateral visual -->
        <aside class="barra-lateral">
            <p class="marca">Mi Tienda · Panel</p>

            <nav class="navegacion" aria-label="Menú administrativo">
                <a class="activo" href="<?= htmlspecialchars(BASE_URL . '/admin', ENT_QUOTES, 'UTF-8') ?>">
                    Resumen
                </a>

<a href="<?= htmlspecialchars(BASE_URL . '/admin/productos', ENT_QUOTES, 'UTF-8') ?>">
    Productos
</a>
<a href="<?= htmlspecialchars(BASE_URL . '/admin/categorias', ENT_QUOTES, 'UTF-8') ?>">
    Categorías
</a>
                <span>Proveedores · Próximamente</span>
                <span>Clientes · Próximamente</span>
                <span>Pedidos · Próximamente</span>
            </nav>
        </aside>

        <div class="contenido">

            <!-- Cabecera -->
            <header class="cabecera">
                <strong>Panel administrativo</strong>
                <a href="<?= $inicio ?>">Ver tienda</a>
            </header>

            <main>
                <p class="etiqueta">Resumen general</p>

                <h1>Bienvenida al panel de Mi Tienda</h1>

                <p class="descripcion">
                    Esta interfaz está preparada para conectar los módulos
                    administrativos de tu tienda.
                </p>

                <div class="aviso">
                    Vista de demostración local: todavía no tiene inicio de
                    sesión ni control de acceso.
                </div>

                <!-- Indicadores visuales sin datos inventados -->
                <section class="tarjetas" aria-label="Resumen de módulos">

                    <article class="tarjeta">
                        <h2>Productos</h2>
                        <strong>—</strong>
                        <p>El indicador se conectará posteriormente a la API.</p>
                    </article>

                    <article class="tarjeta">
                        <h2>Clientes</h2>
                        <strong>—</strong>
                        <p>El indicador se conectará posteriormente a la API.</p>
                    </article>

                    <article class="tarjeta">
                        <h2>Pedidos</h2>
                        <strong>—</strong>
                        <p>El indicador se conectará posteriormente a la API.</p>
                    </article>

                </section>

                <section class="tarjeta accesos">
                    <h2>Acceso rápido</h2>

                    <p>
                        Puedes regresar a la página principal para consultar
                        el catálogo de productos.
                    </p>

                    <a class="enlace" href="<?= $inicio ?>">
                        Ir a la tienda
                    </a>
                </section>
            </main>

        </div>
    </div>
</body>
</html>
