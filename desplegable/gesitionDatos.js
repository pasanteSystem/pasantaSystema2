document.getElementById('gDatos').addEventListener('click', function () {
    // 1. Validamos mediante SweetAlert2 llamando a parent ya que Swal se carga allí
    window.parent.Swal.fire({
        title: 'Acceso a la gestion de datos <br><h6>Solo para administradores</h6>',
        html: `
            <input type="text" id="swal_user_datos" class="swal2-input" placeholder="Usuario">
            <input type="password" id="swal_pass_datos" class="swal2-input" placeholder="Contraseña">
        `,
        showCancelButton: true,
        confirmButtonText: 'Validar',
        showLoaderOnConfirm: true,
        preConfirm: async () => {
            // Buscamos los inputs en el documento padre donde se renderizó el modal
            const u = window.parent.document.getElementById('swal_user_datos').value;
            const p = window.parent.document.getElementById('swal_pass_datos').value;

            try {
                const datos = new FormData();
                datos.append('usuario', u);
                datos.append('password', p);

                const respuesta = await fetch('../validacionDeAdmin/validacion.php', {
                    method: 'POST',
                    body: datos
                });

                const resultado = await respuesta.json();

                if (!resultado.success) {
                    throw new Error(resultado.mensaje || 'Acceso denegado');
                }
                return true;
            } catch (error) {
                window.parent.Swal.showValidationMessage(`Error: ${error.message}`);
            }
        }
    }).then((result) => {
        // 2. Si la validación en el PHP fue exitosa, ejecutamos la apertura del modal
        if (result.isConfirmed) {
            abrirModalGestionDatos();
        }
    });
});

// Función separada para abrir el modal (tu lógica original mejorada)
function abrirModalGestionDatos() {
    let contenedorModal = window.parent.document.getElementById('contenedorDinamico');

    if (!contenedorModal) {
        contenedorModal = window.parent.document.createElement('div');
        contenedorModal.id = 'contenedorDinamico';
        window.parent.document.body.appendChild(contenedorModal);
    }

    contenedorModal.innerHTML = `
        <input type="checkbox" id="ventanaDinamica" style="display:none;" checked>
        <div class="ventanaModal" style="
            display: flex; 
            background-color: rgba(0, 0, 0, 0.8); 
            width: 100%; 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            justify-content: center; 
            align-items: center; 
            z-index: 9999;">
            
            <div style="background: white; padding: 20px; border-radius: 10px; position: relative; width: 80%; height: 80%;">
                <label for="ventanaDinamica" id="btnCerrarGestion" style="
                    cursor: pointer; 
                    font-weight: bold; 
                    color: white; 
                    background: #ff4d4d; 
                    padding: 5px 15px; 
                    border-radius: 5px; 
                    position: absolute; 
                    top: -40px; 
                    right: 0;">Cerrar Gestión</label>
                
                <iframe id="imframAgg" 
                        src="datos/datos.php" 
                        style="width: 100%; height: 100%; border: none;">
                </iframe>
            </div>
        </div>
    `;

    // Lógica para limpiar el contenido al cerrar
    window.parent.document.getElementById('btnCerrarGestion').addEventListener('click', function () {
        contenedorModal.innerHTML = '';
    });
}