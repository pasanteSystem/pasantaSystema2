document.getElementById('toggleConfigPanel').addEventListener('click', function () {
    // 1. Validamos mediante SweetAlert2 llamando a parent ya que Swal se carga allí
    window.parent.Swal.fire({
        title: 'Acceso de Seguridad <br><h6>Solo para administradores</h6>',
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












function toggleConfigPanel() {
    const panel = document.getElementById('customizerPanel');
    if (panel) {
        panel.classList.toggle('open');
    }
}

// 2. APLICAR COLOR PERSONALIZADO
function applyCustomColor(color) {
    const textColor = getContrastYIQ(color);
    const syncCheck = document.getElementById('syncCheck'); // Referencia al checkbox

    // 1. Guardar el color en el navegador
    localStorage.setItem('user-bar-color', color);

    // 2. Aplicar a las variables de la barra y el footer (siempre)
    document.documentElement.style.setProperty('--nav-footer-bg', color);
    document.documentElement.style.setProperty('--nav-footer-text', textColor);

    // 3. SI EL CHECKBOX ESTÁ MARCADO: Aplicar al fondo de la interfaz
    if (syncCheck && syncCheck.checked) {
        // Aplicamos el color al body. 
        // Nota: Si usas una imagen de fondo, esto cambiará el color detrás de la imagen.
        document.body.style.backgroundColor = color;
        document.body.style.backgroundImage = "none"; // Quitamos la imagen para que se vea el color
    }

    // Actualizar el input visualmente
    if (document.getElementById('bgPicker')) {
        document.getElementById('bgPicker').value = color;
    }
}

// También actualizamos la función que se dispara al tocar el switch
function toggleSync() {
    const isSynced = document.getElementById('syncCheck').checked;
    const currentColor = document.getElementById('bgPicker').value;
    applyCustomColor(currentColor);

    localStorage.setItem('sync-theme', isSynced);

    if (isSynced) {
        applyCustomColor(currentColor);
    } else {
        // Si se desactiva, restauramos el fondo original (tu imagen o color base)
        document.body.style.backgroundColor = "";
        document.body.style.backgroundImage = "url('imagenes/fondo1.jpg')";
    }
}

// 3. CAMBIAR TEMAS RÁPIDOS (CLARO/OSCURO)
function setTheme(theme) {
    if (theme === 'dark') {
        applyCustomColor('#1a1a1a'); // Gris casi negro
    } else {
        applyCustomColor('#ffffff'); // Blanco
    }
}

// 4. FUNCIÓN PARA EL CONTRASTE (Texto blanco o negro según el fondo)
function getContrastYIQ(hexcolor) {
    hexcolor = hexcolor.replace("#", "");
    var r = parseInt(hexcolor.substr(0, 2), 16);
    var g = parseInt(hexcolor.substr(2, 2), 16);
    var b = parseInt(hexcolor.substr(4, 2), 16);
    var yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
    // Si el fondo es claro, texto oscuro (#2d3436). Si es oscuro, texto blanco.
    return (yiq >= 128) ? '#2d3436' : '#ffffff';
}

// 5. FUNCIÓN PARA EL BOTÓN DE SINCRONIZACIÓN (Opcional según tu HTML)
function toggleSync() {
    const isSynced = document.getElementById('syncCheck').checked;
    localStorage.setItem('sync-theme', isSynced);
    if (isSynced) {
        console.log("Sincronización activada");
    }
}

// 6. RESTABLECER TODO A LOS VALORES ORIGINALES
function resetEverything() {
    localStorage.removeItem('user-bar-color');
    localStorage.removeItem('sync-theme');

    // Valores por defecto
    document.documentElement.style.setProperty('--nav-footer-bg', '#ffffff');
    document.documentElement.style.setProperty('--nav-footer-text', '#2d3436');

    if (document.getElementById('bgPicker')) {
        document.getElementById('bgPicker').value = '#ffffff';
    }
    if (document.getElementById('syncCheck')) {
        document.getElementById('syncCheck').checked = false;
    }
    Swal.fire({
        title: 'listooooo',
        text: 'restablecido los coloressss',
        icon: 'success',
        confirmButtonColor: '#133bac',
        confirmButtonText: 'chevereeee'
    });
}

// 7. CARGAR CONFIGURACIÓN AL ABRIR LA PÁGINA
document.addEventListener('DOMContentLoaded', () => {
    const savedColor = localStorage.getItem('user-bar-color');
    if (savedColor) {
        applyCustomColor(savedColor);
    }
});