const BASE_URL = "https://dummyjson.com";

// Elementos principales del HTML
const vistaLista = document.getElementById("vista-lista");
const vistaDetalle = document.getElementById("vista-detalle");
const vistaProductos = document.getElementById("vista-productos");

// Categorías que se cargarán al iniciar la página
const CATEGORIAS_API = ["smartphones", "tablets", "mobile-accessories"];

// Mostrar landing y ocultar detalle
function mostrarLista() {
  vistaLista.classList.remove("hidden");
  vistaDetalle.classList.add("hidden");
}

// Ocultar landing y mostrar detalle
function mostrarDetalle() {
  vistaLista.classList.add("hidden");
  vistaDetalle.classList.remove("hidden");
}

// Mostrar indicador de carga
function mostrarSpinner() {
  vistaProductos.innerHTML = `
    <div class="col-span-full flex h-64 items-center justify-center">
      <div class="h-12 w-12 animate-spin rounded-full
                  border-4 border-blue-500 border-t-transparent">
      </div>
    </div>
  `;
}

// Obtener celulares, tablets y accesorios
async function obtenerProductos() {
  try {
    const respuestas = await Promise.all(
      CATEGORIAS_API.map((categoria) =>
        fetch(`${BASE_URL}/products/category/${categoria}`),
      ),
    );

    const datos = await Promise.all(
      respuestas.map((respuesta) => {
        if (!respuesta.ok) {
          throw new Error("Error al obtener productos");
        }

        return respuesta.json();
      }),
    );

    return datos.flatMap((dato) => dato.products);
  } catch (error) {
    console.error("Fallo al obtener productos:", error);
    throw error;
  }
}

// Obtener y mostrar el detalle de un producto
async function verDetalle(id) {
  try {
    const respuesta = await fetch(`${BASE_URL}/products/${id}`);

    if (!respuesta.ok) {
      throw new Error("No se pudo obtener el producto");
    }

    const producto = await respuesta.json();

    document.getElementById("det-imagen").src =
      producto.thumbnail || producto.images[0];

    document.getElementById("det-imagen").alt = producto.title;

    document.getElementById("det-categoria").textContent = producto.category;

    document.getElementById("det-titulo").textContent = producto.title;

    document.getElementById("det-precio").textContent =
      `$${producto.price.toFixed(2)}`;

    document.getElementById("det-desc").textContent = producto.description;

    mostrarDetalle();
  } catch (error) {
    console.error("Error al cargar el detalle:", error);
  }
}

// Crear tarjetas en el catálogo
function renderizarGrid(productos) {
  vistaProductos.innerHTML = productos
    .map(
      (producto) => `
    <article class="rounded-2xl bg-white p-4 shadow-md
                    transition-shadow duration-300 hover:shadow-xl">

      <img src="${producto.thumbnail || producto.images[0]}"
           alt="${producto.title}"
           class="h-48 w-full object-contain">

      <span class="mt-3 inline-block rounded-full bg-blue-100
                   px-2 py-1 text-xs capitalize text-blue-700">
        ${producto.category}
      </span>

      <h3 class="mt-2 font-semibold text-gray-800">
        ${producto.title}
      </h3>

      <p class="mt-1 line-clamp-2 text-sm text-gray-500">
        ${producto.description}
      </p>

      <p class="mt-3 text-lg font-bold text-blue-600">
        $${producto.price.toFixed(2)}
      </p>

      <div class="mt-4">
        <button onclick="verDetalle(${producto.id})"
                class="w-full rounded-lg border border-blue-600
                       px-3 py-2 text-sm font-medium text-blue-600
                       hover:bg-blue-50">
         Ver detalle
        </button>
      </div>
    </article>
  `,
    )
    .join("");
}

// Evento para regresar del detalle al catálogo
document.getElementById("btn-volver").addEventListener("click", () => {
  mostrarLista();
});

// Cargar productos al iniciar la página
document.addEventListener("DOMContentLoaded", async () => {
  mostrarSpinner();

  try {
    const productos = await obtenerProductos();
    renderizarGrid(productos);
  } catch (error) {
    mostrarError();
  }
});
