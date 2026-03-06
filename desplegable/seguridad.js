window.solicitarAcceso = function () {
    Swal.fire({
        title: 'Acceso de Seguridad <br><h6>Solo para administradores</h6>',

        html: `
            <input type="text" id="swal_user" class="swal2-input" placeholder="Usuario">
            <input type="password" id="swal_pass" class="swal2-input" placeholder="Contraseña">
        `,
        showCancelButton: true,
        confirmButtonText: 'Entrar',
        showLoaderOnConfirm: true,
        preConfirm: async () => {
            const u = document.getElementById('swal_user').value;
            const p = document.getElementById('swal_pass').value;

            if (!u || !p) {
                Swal.showValidationMessage('Por favor complete los campos');
                return false;
            }

            try {
                const datos = new FormData();
                // Estos nombres deben coincidir con el $_POST del PHP
                datos.append('usuario', u);
                datos.append('password', p);

                // Ruta: Corrección de la ruta relativa ya que esto se ejecuta en sercoInterfaz.php (raíz del proyecto)
                const respuesta = await fetch('validacionDeAdmin/validacion.php', {
                    method: 'POST',
                    body: datos
                });

                if (!respuesta.ok) {
                    throw new Error('No se pudo conectar con el validador (404/500)');
                }

                const resultado = await respuesta.json();

                if (!resultado.success) {
                    throw new Error(resultado.mensaje);
                }

                return true;
            } catch (error) {
                Swal.showValidationMessage(`Error: ${error.message}`);
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Ejecutamos la apertura del modal
            if (typeof window.abrirModalSeguridad === 'function') {
                window.abrirModalSeguridad();
            } else if (typeof window.parent.abrirModalSeguridad === 'function') {
                window.parent.abrirModalSeguridad();
            }
        }
    });
}