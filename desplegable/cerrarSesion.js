/*CERRAR LA SESION*/

const cerrarSesion = document.getElementById('salir');

cerrarSesion.addEventListener('click', function () {
    window.top.location.href = '../adminLogin/logout.php';

});
