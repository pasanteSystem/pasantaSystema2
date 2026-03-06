// Seleccionamos TODOS los botones de cerrar
const botones = document.querySelectorAll('.botonCerrar');

botones.forEach(boton => {
    boton.addEventListener('click', function () {
        // Desactivamos todos los checkboxes de las ventanas para cerrar cualquiera que esté abierta
        document.getElementById('ventana').checked = false;
        document.getElementById('ventana2').checked = false;
        document.getElementById('ventana3').checked = false;
        window.location.reload();
    });
});