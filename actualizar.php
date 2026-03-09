<?php
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conexion = new mysqli($host, $user, $pass, $db, $port);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $ficha = $_POST['ficha'];
    $depto_id = $_POST['departamento']; // Aquí recibes el ID (ej: 5)
    $sucursal = $_POST['sucursal'];
    $cargo = $_POST['cargo'];
    $observaciones = $_POST['observaciones'] ?? '';

    // 1. BUSCAR EL NOMBRE DEL DEPARTAMENTO
    // Necesitamos el texto (ej: "Sistemas") para la tabla de alojamiento
    $res_dep = $conexion->query("SELECT depart FROM departamentos WHERE id_departamento = '$depto_id'");
    $row_dep = $res_dep->fetch_assoc();
    $nombre_departamento_texto = $row_dep['depart'] ?? 'Sin Depto';

    // ... (lógica de la foto igual) ...

    // 2. ACTUALIZAR TABLA PRINCIPAL (registros)
    $sql1 = "UPDATE registros SET nombre=?, cedula=?, fichaSPI=?, id_departamento=?, sucursal=?, cargo=?, observaciones=?, foto=IFNULL(?, foto) WHERE ID=?";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("ssssssssi", $nombre, $cedula, $ficha, $depto_id, $sucursal, $cargo, $observaciones, $nueva_foto_ruta, $id);
    $stmt1->execute();

    // 3. ACTUALIZAR TABLA DE CARNETS (alojamiento_carnets)
    // Aquí es donde guardamos el NOMBRE de texto para que el carnet cambie
    $sql2 = "UPDATE alojamiento_carnets SET nombre_completo=?, departamento=?, foto_ruta=IFNULL(?, foto_ruta) WHERE cedula=?";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("ssss", $nombre, $nombre_departamento_texto, $nueva_foto_ruta, $cedula);
    $stmt2->execute();

    if ($stmt1->affected_rows >= 0) {
        header("Location: registros.php?msj=actualizado");
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>