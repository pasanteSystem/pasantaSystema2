document.getElementById('registroForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    // 1. Un solo flujo de confirmación para no cansar al usuario
    const { isConfirmed } = await Swal.fire({
        title: '¡Atención!',
        text: 'Es necesario añadir a la fila. si ya esta añadida pulse Aceptar y Guardar para continuar.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Aceptar y Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#133bac',
        cancelButtonColor: '#d33'
    });

    // Si el usuario cancela, nos detenemos aquí
    if (!isConfirmed) return;

    // 2. Preparación y envío de datos
    const formData = new FormData(this);
    formData.append('enviar', '1');

    try {
        const response = await fetch('carnet.php', { method: 'POST', body: formData });
        const data = await response.text();
        const respuesta = data.trim();

        // 3. Manejo de respuestas del servidor
        if (respuesta === 'OK') {
            await Swal.fire({
                title: '¡Registro Exitoso!',
                text: 'Los datos se han guardado correctamente.',
                icon: 'success',
                confirmButtonColor: '#133bac'
            });
            this.reset();
            document.getElementById('miIframe').contentWindow.location.reload();
        }
        else if (respuesta === 'ERROR_DUPLICADO') {
            Swal.fire({ title: '¡Ya existe!', text: 'La ficha ya está registrada.', icon: 'warning', confirmButtonColor: '#f39c12' });
        }
        else {
            throw new Error(respuesta); // Salta al catch
        }

    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo procesar: ' + error.message,
            icon: 'error',
            confirmButtonColor: '#d33'
        });
    }
});