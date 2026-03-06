document.addEventListener('DOMContentLoaded', () => {
    // Variable global para mantener la sucursal seleccionada
    window.sucursalActual = 'todas';
    ejecutarFiltroCombinado();
});


// FUNCION PARA FILTRAR POR SUCURSAL USANDO UNA SELEC
function filtrarSucursal(sucursal) {
    // Actualizamos la variable global con el valor del select
    window.sucursalActual = sucursal;

    // Ejecutamos el filtro combinado que ya tienes programado
    ejecutarFiltroCombinado();
}



// Función que combina la búsqueda de texto y el filtro de sucursal
function ejecutarFiltroCombinado() {
    const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
    const bloques = document.querySelectorAll('.bloque-completo-carnet');
    const noResults = document.getElementById('noResults');
    let contador = 0;

    bloques.forEach(bloque => {
        const etiquetaNombre = bloque.querySelector('.nombre-etiqueta');
        const cuerpoCarnet = bloque.querySelector('.carnet');
        const sucursalBloque = bloque.getAttribute('data-sucursal');

        // 1. Guardar el contenido original para poder limpiar el resaltado después
        if (!etiquetaNombre.dataset.original) etiquetaNombre.dataset.original = etiquetaNombre.innerHTML;
        if (!cuerpoCarnet.dataset.original) cuerpoCarnet.dataset.original = cuerpoCarnet.innerHTML;

        const textoOriginalEtiqueta = etiquetaNombre.dataset.original;
        const textoOriginalCarnet = cuerpoCarnet.dataset.original;

        // Texto plano para la búsqueda (sin etiquetas HTML)
        const textoParaBuscar = (textoOriginalEtiqueta + " " + cuerpoCarnet.innerText).toLowerCase();

        const coincideSucursal = (window.sucursalActual === 'todas' || sucursalBloque === window.sucursalActual);
        const coincideBusqueda = (searchTerm === "" || textoParaBuscar.includes(searchTerm));

        if (coincideSucursal && coincideBusqueda) {
            bloque.style.display = "";
            contador++;

            if (searchTerm !== "") {
                const regex = new RegExp(`(${searchTerm})`, "gi");
                const reemplazo = '<mark class="highlight">$1</mark>';

                // Aplicar resaltado a la etiqueta exterior
                etiquetaNombre.innerHTML = textoOriginalEtiqueta.replace(regex, reemplazo);

                // Aplicar resaltado a TODO el contenido interno del carnet (CI, Depto, etc)
                // Usamos una función para no romper las etiquetas de imagen o estructura
                resaltarTextoInterno(cuerpoCarnet, regex);
            } else {
                etiquetaNombre.innerHTML = textoOriginalEtiqueta;
                cuerpoCarnet.innerHTML = textoOriginalCarnet;
            }
        } else {
            bloque.style.display = "none";
            etiquetaNombre.innerHTML = textoOriginalEtiqueta;
            cuerpoCarnet.innerHTML = textoOriginalCarnet;
        }
    });

    if (noResults) noResults.style.display = (contador === 0) ? "block" : "none";
}

/**
 * Función auxiliar para resaltar solo el texto sin romper el HTML (imágenes, divs)
 */
function resaltarTextoInterno(elemento, regex) {
    const nodos = elemento.querySelectorAll('.valor'); // Buscamos específicamente donde están los datos
    nodos.forEach(nodo => {
        if (!nodo.dataset.original) nodo.dataset.original = nodo.innerHTML;
        nodo.innerHTML = nodo.dataset.original.replace(regex, '<mark class="highlight">$1</mark>');
    });
}


// Escuchar el buscador
document.getElementById('searchInput').addEventListener('input', ejecutarFiltroCombinado);







// Variable para llevar el registro de qué se seleccionó
let historialSeleccion = [];

function seleccionarParaImprimir(elemento) {
    elemento.classList.toggle('seleccionado');
    const bloque = elemento.parentElement;

    if (elemento.classList.contains('seleccionado')) {
        // Guardamos en el historial
        historialSeleccion.push(elemento);

        // Crear parte trasera (Tu código actual de carnetTrasero2.js)
        if (!bloque.querySelector('.carnet-trasero-print')) {
            const trasero = document.createElement('div');
            trasero.className = 'carnet carnet-trasero carnet-trasero-print solo-impresion';
            trasero.innerHTML = `...`; // Aquí va tu HTML de la parte trasera
            bloque.appendChild(trasero);
        }
    } else {
        // Si se deselecciona manualmente, lo quitamos del historial
        historialSeleccion = historialSeleccion.filter(item => item !== elemento);
        const trasera = bloque.querySelector('.carnet-trasero-print');
        if (trasera) trasera.remove();
    }
}

// NUEVA FUNCIÓN: Deseleccionar Todo
function deseleccionarTodo() {
    const seleccionados = document.querySelectorAll('.carnet.seleccionado');
    seleccionados.forEach(carnet => {
        carnet.classList.remove('seleccionado');
        const trasera = carnet.parentElement.querySelector('.carnet-trasero-print');
        if (trasera) trasera.remove();
    });
    historialSeleccion = []; // Limpiamos historial
}

// NUEVA FUNCIÓN: Deshacer último
function deshacerUltimaSeleccion() {
    if (historialSeleccion.length > 0) {
        const ultimo = historialSeleccion.pop(); // Sacamos el último de la lista
        ultimo.classList.remove('seleccionado');
        const trasera = ultimo.parentElement.querySelector('.carnet-trasero-print');
        if (trasera) trasera.remove();
    }
}