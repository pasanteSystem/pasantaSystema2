document.querySelector('.botonCerrar').addEventListener('click', function() {
    window.location.reload();
        // Accedemos a la página principal (parent) y desactivamos el checkbox
        window.parent.document.getElementById('ventana').checked = false;
        window.parent.document.getElementById('ventana2').checked = false;

        
    });