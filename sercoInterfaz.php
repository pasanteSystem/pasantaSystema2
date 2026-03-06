<?php
require_once 'adminLogin/verificar_sesion.php';

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sercoinfal - Dashboard</title>
    <link rel="stylesheet" href="sercoInterfaz.css">
    <link href="" rel="stylesheet">
</head>
<body>
<!-- NAVEGADPR EN LA BARRA SUPERIOR-->
    <div class="navegador">
        <div class="nav-left">
            <label id="ventanaAbrir" for="ventana" class="nave">REGISTROS</label>
            <label for="ventana2"for="ventana"class="nave">NUEVO</label>
            <label for="ventana3"class="nave">GUARDA/CARNET</label>
        </div>
        
        <div class="nav-central">
            <h1 class="logo-text">SERCOINFAL</h1>
        </div>

        <div class="nav-derecho">
        <div class="nomover">
                <label id="btnConfig" for="btn3" class="nave">CONFIGURACION</label>
                <ul id="menuConfig"> 
                    <li>  <iframe id="desplegable" class="desplegable" src="desplegable/desplegableConfig.php" frameborder="0"></iframe></li>
                </ul>
            </div>
        </div>
    </nav>

<!-- VENTANA MODAL DE REGISTRO ACTUALES-->
 <input type="checkbox" id="ventana" ></input>
<div class="ventanaModal" >
        <div class="cerrarr">
            <button class="botonCerrar">cerrar</button>
            <iframe id="imframRegistro" class="imframRegistro" src="registros.php" frameborder="0" ></iframe>
            
        </div>
    </div>

</div>

<!-- VENTANA2 MODAL DE NUEVOS REGISTROS -->
    <input type="checkbox" id="ventana2"></input>
        <div id="ventanaModal2" class="ventanaModal2">
    <div class="cerrarr">
        <button class="botonCerrar">cerrar</button>
        <iframe id="imframFormm" class="registroForm" src="createCarnet/carnet.php" frameborder="0"></iframe>
    </div>
</div>

<!-- VENTANA3 MODAL DE NUEVOS REGISTROS -->
    <input type="checkbox" id="ventana3"></input>
        <div id="ventanaModal3" class="ventanaModal3">
            <div class="cerrarr">
            <button class="botonCerrar">cerrar</button>
            <iframe id="listCarnet" class="listaCarnet" src="listaAlojamiento/listaAlojamiento.php" frameborder="0"></iframe>
            </div>
        </div>

<!-- LOGOS DEL MAIN -->
    <main class="container"><script src=""></script>

        <div class="contentImg">
            <img src="imagenes/serco.jpg" alt="" class="img1">
        </div>

        <div class="contentImg">
            <img src="imagenes/carnet.jpg" alt="" class="img1">
        </div>
    </main>
<!-- BARRA FINAL -->
<footer class="footer-interfaz">
    <h4 style="display:flex; justify-content:center;">WWW.SERCOINFAL C.A.</h4>
    <p>&copy; <?php echo date("Y"); ?> Todos los derechos reservados.</p>



<link rel="stylesheet" href="temas/tema.css">

<button class="config-toggle" onclick="toggleConfigPanel()">
    <i class="fa-solid fa-wand-magic-sparkles"></i>
</button>


<div id="customizerPanel" class="customizer-panel">
    <div class="customizer-header">
        <h3>Apariencia</h3>
        <button onclick="toggleConfigPanel()" class="close-btn">&times;</button>
    </div>

    <div class="customizer-body">
        <div class="config-section">
            <p class="section-title">Tema de Sistema</p>
            <div class="mode-options">
                <button class="btn-mode" onclick="setTheme('light')">☀️ Claro</button>
                <button class="btn-mode" onclick="setTheme('dark')">🌙 Oscuro</button>
            </div>
        </div>

        <div class="config-section">
            <p class="section-title">Personalización de Fondo</p>
            <p style="font-size: 0.8rem; margin-bottom: 10px;">Elige un color para el fondo:</p>
            <input type="color" class="bg-picker" id="bgPicker" onchange="applyCustomColor(this.value)">
            
            <div class="sync-option">
            </div>

            <button class="btn-reset" onclick="resetEverything()">Restablecer Todo</button>
        </div>
    </div>
</div>
<script src="temas/tema.js"></script>
</footer>




<script src="cerrarRegistro.js"></script>
<script src="imagenVista.js"></script>
<script src="sercoinnterfaz.js"></script>
<script src="sercoinnterfaz2.js"></script>
<script src="desplegable/desplegable.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="desplegable/gesitionDatos.js"></script>
<script src="desplegable/seguridadVentana.js"></script>
<script src="desplegable/seguridad.js"></script>

</body>

</html>


<?php
$servidor = "localhost";
?>
