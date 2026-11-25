// La ruta es relativa a index.html, ubicado en la raíz del proyecto.
const API_PRODUCTOS = new URL(
  "./public/api/productos",
  document.baseURI,
).href;

// Elementos principales de la página.
const vistaLista = document.getElementById("vista-lista");
const vistaDetalle = document.getElementById("vista-detalle");
const vistaProductos = document.getElementById("vista-productos");
const mensajeCatalogo = document.getElementById("mensaje-catalogo");
const filtrosCategorias = document.getElementById("filtros-categorias");

let productosCargados = [];
let categoriaSeleccionada = "todos";

// Formato de precios en soles.
const formatoPrecio = new Intl.NumberFormat("es-PE", {
  style: "currency",
  currency: "PEN",
});

function mostrarLista() {
  vistaLista.classList.remove("hidden");
  vistaDetalle.classList.add("hidden");
}

function mostrarDetalle() {
  vistaLista.classList.add("hidden");
  vistaDetalle.classList.remove("hidden");

  window.scrollTo({ top: 0, behavior: "smooth" });
}

function mostrarMensaje(texto) {
  mensajeCatalogo.textContent = texto;
}

function formatearPrecio(valor) {
  if (valor === null || valor === undefined || valor === "") {
    return "Precio no disponible";
  }

  const precio = Number(valor);

  if (!Number.isFinite(precio)) {
    return "Precio no disponible";
  }

  return formatoPrecio.format(precio);
}

// Solo se aceptan direcciones de imagen HTTP o HTTPS.
function obtenerUrlImagen(valor) {
  if (typeof valor !== "string" || valor.trim() === "") {
    return null;
  }

  try {
    const url = new URL(valor);

    if (url.protocol === "http:" || url.protocol === "https:") {
      return url.href;
    }
  } catch {
    return null;
  }

  return null;
}

function crearElemento(etiqueta, clases, texto = "") {
  const elemento = document.createElement(etiqueta);

  elemento.className = clases;
  elemento.textContent = texto;

  return elemento;
}

function crearImagen(producto) {
  const imagen = document.createElement("img");
  const url = obtenerUrlImagen(producto.imagen);

  imagen.className = "h-48 w-full object-contain";
  imagen.alt = producto.nombre || "Producto";
  imagen.loading = "lazy";

  if (url) {
    imagen.src = url;

    imagen.addEventListener("error", () => {
      imagen.hidden = true;
    });
  } else {
    imagen.hidden = true;
  }

  return imagen;
}

// Consulta la API y comprueba la respuesta HTTP y el formato JSON.
async function consultarApi(url) {
  const respuesta = await fetch(url, {
    headers: {
      Accept: "application/json",
    },
  });

  const resultado = await respuesta.json();

  if (!respuesta.ok || resultado.success !== true) {
    throw new Error(resultado.message || "No se pudo completar la consulta");
  }

  return resultado.data;
}

// Obtiene los productos activos de la API.
async function obtenerProductos() {
  const productos = await consultarApi(API_PRODUCTOS);

  if (!Array.isArray(productos)) {
    throw new Error("La API no devolvió una lista de productos");
  }

  return productos;
}

// Muestra el detalle consultando nuevamente la API por ID.
async function verDetalle(id) {
  try {
    mostrarMensaje("Cargando detalle del producto...");

    const producto = await consultarApi(
      `${API_PRODUCTOS}/${encodeURIComponent(id)}`,
    );

    if (!producto || typeof producto !== "object") {
      throw new Error("La API no devolvió el producto");
    }

    const imagenDetalle = document.getElementById("det-imagen");
    const urlImagen = obtenerUrlImagen(producto.imagen);

    imagenDetalle.alt = producto.nombre || "Producto";

    if (urlImagen) {
      imagenDetalle.hidden = false;
      imagenDetalle.src = urlImagen;

      imagenDetalle.onerror = () => {
        imagenDetalle.hidden = true;
      };
    } else {
      imagenDetalle.removeAttribute("src");
      imagenDetalle.hidden = true;
    }

    document.getElementById("det-categoria").textContent =
      producto.categoria || "Sin categoría";

    document.getElementById("det-titulo").textContent =
      producto.nombre || "Producto sin nombre";

    document.getElementById("det-precio").textContent =
      formatearPrecio(producto.precio);

    document.getElementById("det-desc").textContent =
      producto.descripcion || "Este producto no tiene descripción.";

    // La tabla de productos no contiene una calificación.
    document.getElementById("det-rating").textContent = "";

    mostrarMensaje("");
    mostrarDetalle();
  } catch (error) {
    console.error("Error al cargar el detalle:", error);
    mostrarMensaje("No se pudo cargar el detalle. Inténtalo nuevamente.");
  }
}

// Construye las tarjetas del catálogo.
function renderizarGrid(productos) {
  vistaProductos.replaceChildren();

  if (productos.length === 0) {
    mostrarMensaje("No hay productos disponibles en esta categoría.");
    return;
  }

  mostrarMensaje(`${productos.length} producto(s) encontrado(s).`);

  productos.forEach((producto) => {
    const tarjeta = crearElemento(
      "article",
      "rounded-2xl bg-white p-4 shadow-md transition-shadow duration-300 hover:shadow-xl",
    );

    const categoria = crearElemento(
      "span",
      "mt-3 inline-block rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-700",
      producto.categoria || "Sin categoría",
    );

    const nombre = crearElemento(
      "h3",
      "mt-2 font-semibold text-gray-800",
      producto.nombre || "Producto sin nombre",
    );

    const descripcion = crearElemento(
      "p",
      "mt-1 line-clamp-2 text-sm text-gray-500",
      producto.descripcion || "Sin descripción",
    );

    const precio = crearElemento(
      "p",
      "mt-3 text-lg font-bold text-blue-600",
      formatearPrecio(producto.precio),
    );

    const contenedorBoton = crearElemento("div", "mt-4");

    const boton = crearElemento(
      "button",
      "w-full rounded-lg border border-blue-600 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50",
      "Ver detalle",
    );

    boton.type = "button";
    boton.addEventListener("click", () => {
      verDetalle(producto.id_producto);
    });

    contenedorBoton.append(boton);

    tarjeta.append(
      crearImagen(producto),
      categoria,
      nombre,
      descripcion,
      precio,
      contenedorBoton,
    );

    vistaProductos.append(tarjeta);
  });
}

// Aplica el filtro sobre la lista recibida.
function aplicarFiltro() {
  const filtrados =
    categoriaSeleccionada === "todos"
      ? productosCargados
      : productosCargados.filter(
          (producto) => producto.categoria === categoriaSeleccionada,
        );

  renderizarGrid(filtrados);
}

// Actualiza el aspecto de los botones de filtro.
function actualizarBotonesFiltro() {
  const botones = filtrosCategorias.querySelectorAll("button[data-categoria]");

  botones.forEach((boton) => {
    const seleccionado = boton.dataset.categoria === categoriaSeleccionada;

    boton.setAttribute("aria-pressed", String(seleccionado));

    boton.className = seleccionado
      ? "rounded-full bg-blue-600 px-4 py-1.5 text-sm font-medium text-white"
      : "rounded-full bg-white px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100";
  });
}

// Detecta el clic en una categoría.
filtrosCategorias.addEventListener("click", (evento) => {
  const boton = evento.target.closest("button[data-categoria]");

  if (!boton || !filtrosCategorias.contains(boton)) {
    return;
  }

  categoriaSeleccionada = boton.dataset.categoria;

  actualizarBotonesFiltro();
  aplicarFiltro();
});

// Regresa desde el detalle al catálogo.
document.getElementById("btn-volver").addEventListener("click", () => {
  mostrarLista();
});

// Carga inicial del catálogo.
async function iniciarCatalogo() {
  actualizarBotonesFiltro();
  mostrarMensaje("Cargando productos...");

  try {
    productosCargados = await obtenerProductos();
    aplicarFiltro();
  } catch (error) {
    console.error("Error al cargar productos:", error);
    vistaProductos.replaceChildren();
    mostrarMensaje(
      "No se pudieron cargar los productos. Revisa que Apache, MySQL y la API estén funcionando.",
    );
  }
}

iniciarCatalogo();
