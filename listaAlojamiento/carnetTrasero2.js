function seleccionarParaImprimir(elemento) {
    // Alternamos la selección del carnet frontal
    elemento.classList.toggle('seleccionado');
    const bloque = elemento.parentElement;

    if (elemento.classList.contains('seleccionado')) {
        // Si se selecciona, creamos el trasero idéntico a visualCarnet2
        if (!bloque.querySelector('.carnet-trasero-print')) {
            const trasero = document.createElement('div');
            // 'carnet-trasero' para los estilos de visualCarnet.css
            // 'solo-impresion' para que desaparezca en la pantalla
            trasero.className = 'carnet carnet-trasero carnet-trasero-print solo-impresion';

            trasero.innerHTML = `
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
            bloque.appendChild(trasero);
        }
    } else {
        // Si se deselecciona, eliminamos el trasero generado
        const traseraExistente = bloque.querySelector('.carnet-trasero-print');
        if (traseraExistente) traseraExistente.remove();
    }
}