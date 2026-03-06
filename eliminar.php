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

if(isset($_GET['ID'])) {
    $id = intval($_GET['ID']);

    // 1. Eliminamos el registro
    $sql_delete = "DELETE FROM registros WHERE ID = $id";

    if ($conexion->query($sql_delete)) {
        
        // --- PROCESO FORZADO DE REENUMERACIÓN ---

        // A. Quitar la propiedad AUTO_INCREMENT temporalmente para poder modificar los IDs
        $conexion->query("ALTER TABLE registros MODIFY ID INT NOT NULL");

        // B. Quitar la llave primaria temporalmente
        $conexion->query("ALTER TABLE registros DROP PRIMARY KEY");

        // C. Reiniciar el contador y reasignar IDs en orden
        $conexion->query("SET @count = 0");
        $conexion->query("UPDATE registros SET ID = (@count := @count + 1)");

        // D. Volver a poner la llave primaria y el AUTO_INCREMENT
        $conexion->query("ALTER TABLE registros ADD PRIMARY KEY (ID)");
        $conexion->query("ALTER TABLE registros MODIFY ID INT NOT NULL AUTO_INCREMENT");

        // E. Ajustar el siguiente número inicial del AUTO_INCREMENT
        $resultado = $conexion->query("SELECT MAX(ID) AS max_id FROM registros");
        $fila = $resultado->fetch_assoc();
        $proximo_id = ($fila['max_id'] ? $fila['max_id'] : 0) + 1;
        $conexion->query("ALTER TABLE registros AUTO_INCREMENT = $proximo_id");

        $conexion->close();
        header("Location: registros.php?mensaje=eliminado");
        exit(); 
    } else {
        echo "Error al eliminar registro: " . $conexion->error;
    }
}
?>