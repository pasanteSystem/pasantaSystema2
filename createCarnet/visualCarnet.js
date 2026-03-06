
document.getElementById('imprimir').addEventListener('click', function () {
    window.print();
});

//ESTO ES PARA ACTULIZAR LA DOCUMENTACION QUE SE VA A VER EN EL CARNET
function actualizar() {
    // Referencia al documento padre (donde están los inputs)
    const padre = window.parent.document;
    const fecha = new Date();
    const fecha2 = new Date();

    // Obtener valores
    const nombre = padre.getElementById('txtNombre').value;
    const apellido = padre.getElementById('txtApellido').value;
    const cedula = padre.getElementById('txtCedula').value;
    const ficha = padre.getElementById('txtFicha').value;
    const Departamento = padre.getElementById('departamento').value;

    // Asignar al documento actual (el iframe)

    document.getElementById('inNombreCompleto').innerText = `${nombre} ${apellido}`;
    document.getElementById('inCedula').innerText = "V-" + cedula || "00.000.000";
    document.getElementById('inFicha').innerText = ficha || "ficha";
    document.getElementById('inDepartamento').innerText = Departamento || "departamento";


}


//EVENTO PARA LIMPIAR LAS IMAGENES DE LOS DOS LADOS
function previewImage(event) {
    const limpiarImg = document.getElementById('limpiar')
    const reader = new FileReader();
    const file = event.target.files[0];

    reader.onload = function () {


        const iframe = document.getElementById('miIframe');
        // Buscamos la etiqueta img dentro del iframe (asegúrate que tenga el ID 'inFoto')

        const fotoCarnet = iframe.contentWindow.document.getElementById('inFoto');

        if (fotoCarnet) {
            fotoCarnet.src = reader.result;
        }
    }
    if (file) {
        reader.readAsDataURL(file);
    }

}
//LIMPIAR LA IMAGEN DE LA IFRAME OSEA LA DEL CARNET
document.getElementById('limpiar').addEventListener('click', function () {
    const iframe = document.getElementById('miIframe');
    const docIframe = iframe.contentWindow.document;

    // LIMPIAR LA FOTO
    const fotoCarnet = docIframe.getElementById('inFoto');
    if (fotoCarnet) {
        fotoCarnet.src = "";
    }
});


