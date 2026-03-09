<?php
require_once '../adminLogin/verificar_sesion.php';
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="visualCarnet.css">
</head>
<body>

    <div class="carnet">
        <div class="fondo-patron"></div>

        <div class="header">
           
        </div>

        <div class="foto-box">
            <img id="inFoto" alt="">
        </div>

        <div class="datos-empleado">
            <div class="campo2">
                <span class="label"></span>
                <span id="inNombreCompleto" class="valor" ></span>

            </div>

            <div class="campo">
                <span class="label"></span>
                <span id="inCedula" class="valor"></span>
            </div>

            <div class="campo">
                <div class="campo">
                    <span class="label"></span>
                    <span id="inFicha" class="valor"></span>
                </div>
                <div class="campo">
                    <span class="label"></span>
                    <span id="inDepartamento" class="valor"></span>
                </div>
            </div>
        </div>

        <div class="footerr">
            SERVICIO DE ALIMENTACIÓN
        </div>
    </div>

    <button id="imprimir" class="imprimir">  Imprimir </button>
    <button onclick="añadirACola()" class="imprimir" style="background-color: #10b981;"> Añadir a la fila </button>
    <div id="colaImpresion" class="hoja-impresion"></div>
    <script src="visualCarnet.js" ></script>
    <script src="visualCarnet2.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

<?php
$servidor = "localhost";
?>