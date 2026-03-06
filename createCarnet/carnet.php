<?php

require_once '../adminLogin/verificar_sesion.php';

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conexion = new mysqli($host, $user, $pass, $db, $port);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}



date_default_timezone_set('America/Caracas');

// --- PROCESAMIENTO DE DATOS ---
if (isset($_POST['enviar'])) {
    $ficha = $_POST['ficha'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula'];
    $departamento = $_POST['departamento'];
    $cargo = $_POST['cargo'];
    $sucursal = $_POST['sucursal'];
    $observ = $_POST['observ'];
    $fecha = date("Y-m-d H:i:s");

    $nombre_imagen = $_FILES['foto']['name'] ?? '';
    $temp_imagen = $_FILES['foto']['tmp_name'] ?? '';
    $carpeta_destino = 'uploads/';

    // 1. VALIDACIÓN
    $consulta_verificar = "SELECT fichaSPI FROM registros WHERE fichaSPI = '$ficha' LIMIT 1";
    $resultado_verificar = mysqli_query($conexion2, $consulta_verificar);

    if (mysqli_num_rows($resultado_verificar) > 0) {
        echo "ERROR_DUPLICADO"; // Enviamos un código simple
        exit;
    }

    // 2. PROCESAR IMAGEN
    $sql_foto = "";
    if ($nombre_imagen != "") {
        if (!file_exists($carpeta_destino)) { mkdir($carpeta_destino, 0777, true); }
        $nombre_unico = time() . "_" . $nombre_imagen;
        $ruta_final = $carpeta_destino . $nombre_unico;
        if (move_uploaded_file($temp_imagen, $ruta_final)) {
            $sql_foto = $ruta_final;
        }
    }

    // 3. GUARDAR
   $insertarDatos = "INSERT INTO registros (fichaSPI, nombre, cedula, id_departamento, cargo, sucursal, observaciones, foto, fecha_registro) 
                  VALUES ('$ficha', '$nombre $apellido', '$cedula', '$departamento', '$cargo', '$sucursal', '$observ', '$sql_foto', '$fecha')";
    $sql_alojamiento = "INSERT INTO alojamiento_carnets (nombre_completo, cedula, departamento, foto_ruta) VALUES ('$nombre $apellido', '$cedula', '$departamento', '$sql_foto')";

    if (mysqli_query($conexion2, $insertarDatos) && mysqli_query($conexion2, $sql_alojamiento)) {
        echo "OK"; // Solo texto plano
        exit;
    } else {
        echo "ERROR_SQL: " . mysqli_error($conexion2);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Carnetización</title>
    <link rel="stylesheet" href="carnet.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container">
    <h2>Registro de Nuevo Carnet</h2>
    
    <form class="formulario" id="registroForm" method="post" enctype="multipart/form-data">
        <div class="form">
            <div class="seccionFoto">
                <div class="photo" id="photo">
                    <span style="color: #94a3b8;">Sin foto</span>
                </div>
                <label for="foto">Subir Foto del Empleado</label>
                <input class="inputs" name="foto" type="file" id="foto" accept="image/*" onchange="previewImage(event)" oninput="document.getElementById('miIframe').contentWindow.actualizar()">
            </div>

            <div class="grupoInput">
                <label>Cédula / CI</label>
                <input id="txtCedula" class="inputs" name="cedula" type="number" placeholder="Ingrese documento" required oninput="document.getElementById('miIframe').contentWindow.actualizar()">
            </div>

            <div class="grupoInput">
                <label>Ficha SP1</label>
                <input id="txtFicha" class="inputs" name="ficha" type="text" placeholder="Código de ficha" oninput="document.getElementById('miIframe').contentWindow.actualizar()">
            </div>

            <div class="nombreA">
                <label>Nombre Completo</label>
                <div class="nombreA2">
                    <input id="txtNombre" class="nombreApellido" name="nombre" type="text" placeholder="Nombres" required oninput="document.getElementById('miIframe').contentWindow.actualizar()">
                    <input id="txtApellido" class="nombreApellido" name="apellido" type="text" placeholder="Apellidos" required oninput="document.getElementById('miIframe').contentWindow.actualizar()">
                </div>
            </div>

            <div class="grupoInput">
                <label>Departamento</label>
                <select id="departamento" name="departamento" required>
    <option value="">Seleccione...</option>
    <?php 
    // Asegúrate de que la conexión sea a la base de datos correcta
    $db_dept = new mysqli($servidor, $user, $pass, "registroscarnet"); // Usa tu BD principal
    $res_dept = $db_dept->query("SELECT id_departamento, depart FROM departamentos"); 
    
    while($row = $res_dept->fetch_assoc()) {
        // El 'value' debe ser el ID numérico para que la relación (FK) no falle
        echo "<option value='".$row['id_departamento']."'>".$row['depart']."</option>";
    }
    ?>
</select>
            </div>

            <div class="grupoInput">
                <label>Cargo</label>
                <input id="txtCargo" class="inputs" name="cargo" type="text" placeholder="Puesto de trabajo" required oninput="document.getElementById('miIframe').contentWindow.actualizar()">
            </div>

            <div class="grupoInput">
                <label>Sucursal</label>
                <select name="sucursal" id="textSucursal" class="sucursal" required>
                    <option value="">Seleccione...</option>
                    <?php 
                    $res_suc = $conexion2->query("SELECT sucurs FROM sucursales");
                    while($row = $res_suc->fetch_assoc()) {
                        echo "<option value='".$row['sucurs']."'>".$row['sucurs']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="grupoInput full-width">
                <label>Observaciones</label>
                <textarea name="observ" rows="3" placeholder="Notas adicionales..." oninput="document.getElementById('miIframe').contentWindow.actualizar()" ></textarea>
            </div>
        </div>

        <button name="enviar" type="submit">Guardar Registro</button>
        <button type="reset" class="" id="limpiar">limpiar</button>
    </form>
</div> 

<div class="carnetVisual"><iframe id="miIframe" class="visualIframe" src="visualCarnet.php"></iframe></div>

<script src="../datos/datos.js"></script>
<script src="cerrarRegistro.js"></script>   
<script src="visualCarnet.js"></script>
<script src="visualCarnet2.js"></script>
<script src="evitarRecargaPagina.js"></script>
</body>
</html>

<?php
$servidor = "localhost";
?>