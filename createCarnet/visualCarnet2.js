

function añadirACola() {
    const carnetOriginal = document.querySelector('.carnet');
    const cola = document.getElementById('colaImpresion');

    // 1. Creamos un contenedor para la PAREJA (Frente + Trasero)
    const contenedorPareja = document.createElement('div');
    contenedorPareja.style.display = "flex";
    contenedorPareja.style.flexDirection = "column";
    contenedorPareja.style.alignItems = "center";
    contenedorPareja.style.gap = "5px"; // Espacio pequeño entre frente y dorso
    contenedorPareja.className = "pareja-carnet";

    // 2. Clonamos el frontal
    const nuevoCarnetFrontal = carnetOriginal.cloneNode(true);
    nuevoCarnetFrontal.classList.add('clonado');

    // 3. Creamos el trasero
    const carnetTrasero = document.createElement('div');
    carnetTrasero.className = 'carnet carnet-trasero clonado';
    carnetTrasero.innerHTML = `
        <div class="content" style="margin-top:20px;">
            <img src="imagenes/serco.jpg" alt="" style="width: 80px; height: 40px; margin: 1px auto; display: block;">
            <p style="font-size: 10px; margin: 2px;">SERCOINFAL, C.A. es una empresa de servicios del área de alimentación.</p>
            <p style="font-size: 10px; margin: 2px;">Este carnet es propiedad de la empresa, de uso personal e instransferible. Por ende, debe ser devuelto
            al finalizar la relacion laboral</p>
            <p style="font-size: 10px; margin: 2px;">Se le agradece a las autoridades civiles y militares prestar la mayor colaboración al titular de este carnet
            para el buen desempeño de sus funciones.</p>
            <p style="font-weight: bold; margin: 2px;">SERCOINFAL C.A</p>
            <p style="margin: 2px;">J-30591055-3</p>
            <p style="margin: 2px;">0241-6141507</p>
        </div>
        <footer style="color: white; font-weight: 600; display: flex; text-align: center; align-items: center; justify-content: center; background-color: rgb(19, 59, 172); height: 15px; width: 100%; margin-top: auto; font-size: 9px;">
            WWW.SERCOINFAL.COM
        </footer>
    `;

    // 4. Metemos ambos en el contenedor de pareja y luego a la cola
    contenedorPareja.appendChild(nuevoCarnetFrontal);
    contenedorPareja.appendChild(carnetTrasero);
    cola.appendChild(contenedorPareja);

    // 5. Salto de página cada 5 parejas (si caben 5 por ancho de hoja)
    const totalParejas = cola.querySelectorAll('.pareja-carnet').length;
    if (totalParejas % 5 === 0) {
        const salto = document.createElement('div');
        salto.style.width = "100%";
        salto.style.breakAfter = "page";
        cola.appendChild(salto);
    }
    if (cola) {
        Swal.fire({
            title: '¡Añadido!',
            text: 'Carnet (Frente y Trasero) añadido a la fila.',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Genial'
        });

    }
    window.parent.document.getElementById('inDepartamento').display = 'none';
    actualizar();

}




