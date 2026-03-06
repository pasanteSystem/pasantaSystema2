document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('inventoryBody');
    const rows = tableBody.getElementsByTagName('tr');

    // Guardamos el contenido original de las celdas
    const originalContent = Array.from(rows).map(row => {
        return Array.from(row.cells).map(cell => cell.innerHTML);
    });

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim().toLowerCase();

        Array.from(rows).forEach((row, rowIndex) => {

            // --- NUEVO BLOQUE DE CONTROL ---
            // Si la fila es un separador de mes, la mostramos siempre o la ocultamos según la búsqueda
            if (row.classList.contains('row-mes-separador')) {
                // Opcional: Ocultar el título del mes si estamos buscando algo específico
                row.style.display = searchTerm === "" ? "" : "none";
                return;
            }

            let matchFound = false;
            const cells = row.cells;

            Array.from(cells).forEach((cell, cellIndex) => {
                const originalHTML = originalContent[rowIndex][cellIndex];

                if (cell.classList.contains('actions-cell') || cell.querySelector('img')) {
                    return;
                }

                if (searchTerm === "") {
                    cell.innerHTML = originalHTML;
                    matchFound = true;
                } else {
                    const text = originalHTML.replace(/<[^>]*>/g, "");
                    const index = text.toLowerCase().indexOf(searchTerm);

                    if (index !== -1) {
                        matchFound = true;
                        const regex = new RegExp(`(${searchTerm})`, "gi");
                        cell.innerHTML = originalHTML.replace(regex, '<mark class="highlight">$1</mark>');
                    } else {
                        cell.innerHTML = originalHTML;
                    }
                }
            });

            row.style.display = matchFound ? "" : "none";
        });
    });
});
function abrirEditar(datos) {
    document.getElementById('edit_id').value = datos.ID;
    document.getElementById('edit_nombre').value = datos.nombre;
    document.getElementById('edit_cedula').value = datos.cedula;
    document.getElementById('edit_ficha').value = datos.fichaSPI;
    document.getElementById('edit_depto').value = datos.id_departamento;
    document.getElementById('edit_sucursal').value = datos.sucursal;
    document.getElementById('edit_cargo').value = datos.cargo;

    // Mostrar vista previa de la foto actual
    const preview = document.getElementById('edit_preview');
    if (datos.foto) {
        preview.src = "createCarnet/" + datos.foto;
        preview.style.display = "block";
    } else {
        preview.style.display = "none";
    }

    // Limpiar el input de archivo por si había algo seleccionado
    document.getElementById('edit_foto').value = "";

    document.getElementById('modalEditar').style.display = 'block';
}

function cerrarEditar() {
    document.getElementById('modalEditar').style.display = 'none';
}