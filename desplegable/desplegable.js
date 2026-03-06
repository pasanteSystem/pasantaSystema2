
const btnConfig = document.getElementById('btnConfig');
const menuConfig = document.getElementById('menuConfig');
const cerrarSesion = document.getElementById('salir');


// ABRIR MENU DESPLEGABLE
btnConfig.addEventListener('click', (e) => {
    e.stopPropagation(); // Evita que el clic cierre el menú inmediatamente
    menuConfig.classList.toggle('activo');
});

// CERRAR MENU DESPLEGABLE
document.addEventListener('click', (e) => {
    if (!menuConfig.contains(e.target) && e.target !== btnConfig) {
        menuConfig.classList.remove('activo');
    }


});

const gDatos = document.getElementById('gDatos');

gDatos.addEventListener('click', function () {

    const nuevoD = document.createElement('div');
    nuevoD.className = 'nuevo-Departamento';
    nuevoD.id = 'contenedorModalExterno';

    nuevoD.innerHTML = `
        <input type="checkbox" id="ventana3"  Style= "display: flex;  "></input>
        <div id="ventanaModal3" class="ventanaModal3"  Style= "     transition: opacity 0.5s ease, transform 0.5s ease, visibility 0.5s; background-color:rgba(0, 0, 0, 0.8); width: 100px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;

        justify-content: center;
        align-items: center;
        z-index: 100; ">
            
                <iframe id="imfraGGD" class="infraGGD" src="../datos/datos.php" frameborder="0" Style= ""></iframe>
            </div>`;

    document.body.appendChild(nuevoD);
});