window.abrirModalSeguridad = function () {
    const contenedor = document.getElementById('contenedorDinamicoSeguridad') ||
        (function () {
            const div = document.createElement('div');
            div.id = 'contenedorDinamicoSeguridad';
            document.body.appendChild(div);
            return div;
        })();

    contenedor.innerHTML = `
        <input type="checkbox" id="ventana4" checked style="display:none;"></input>
        <div class="ventanaModal4">
            <div class="cerrarr">
                <button class="botonCerrar" id="btnCerrarSeg">cerrar</button>
                <iframe id="imframRegistro" class="imframRegistro" src="seguridad/seguridad.php" frameborder="0"></iframe>
            </div>
        </div>
    `;

    // Asignamos el evento de cerrar directamente a este botón recién creado
    document.getElementById('btnCerrarSeg').onclick = function () {
        // Desmarcamos el checkbox para que la ventana haga la transición de cierre
        document.getElementById('ventana4').checked = false;

        // Esperamos medio segundo para que termine la animación de CSS antes de eliminar el HTML
        setTimeout(() => {
            contenedor.innerHTML = '';
        }, 500);
    };
};