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


if(isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. Eliminamos el registro
    $sql_delete = "DELETE FROM departamento WHERE id = $id";

    if ($conexion->query($sql_delete)) {
        
        // --- PROCESO FORZADO DE REENUMERACIÓN ---

        // A. Quitar la propiedad AUTO_INCREMENT temporalmente para poder modificar los IDs
        $conexion->query("ALTER TABLE departamento MODIFY id INT NOT NULL");

        // B. Quitar la llave primaria temporalmente
        $conexion->query("ALTER TABLE departamento DROP PRIMARY KEY");

        // C. Reiniciar el contador y reasignar IDs en orden
        $conexion->query("SET @count = 0");
        $conexion->query("UPDATE departamento SET id = (@count := @count + 1)");

        // D. Volver a poner la llave primaria y el AUTO_INCREMENT
        $conexion->query("ALTER TABLE departamento ADD PRIMARY KEY (id)");
        $conexion->query("ALTER TABLE departamento MODIFY id INT NOT NULL AUTO_INCREMENT");

        // E. Ajustar el siguiente número inicial del AUTO_INCREMENT
        $resultado = $conexion->query("SELECT MAX(id) AS max_id FROM departamento");
        $fila = $resultado->fetch_assoc();
        $proximo_id = ($fila['max_id'] ? $fila['max_id'] : 0) + 1;
        $conexion->query("ALTER TABLE departamento AUTO_INCREMENT = $proximo_id");

        $conexion->close();
        header("Location: datos.php?mensaje=eliminado");
        exit(); 
    } else {
        echo "Error al eliminar registro: " . $conexion->error;
    }
}
?>