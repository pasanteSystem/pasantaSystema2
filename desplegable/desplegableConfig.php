
<?php
require_once '../adminLogin/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <link rel="stylesheet" href="desplegableConfig.css">
<div class="config-container">
    <aside class="config-sidebar">
        <button id="gDatos" class="tab-btn">Gestión de Datos</button>   
        <button id="seguridad" class= "tab-btn" onclick="window.parent.solicitarAcceso()" >Seguridad</button>
        <button id="salir" class= "tab-btn" >salir</button>
    </aside>    
</div>
    <script src="gesitionDatos.js"></script>
    <script src="cerrarSesion.js"></script>
    <script src="desplegable.js"></script>
</body>
</html>
<?php
$servidor = "localhost";
?>

