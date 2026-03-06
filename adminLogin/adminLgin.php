<?php
session_start();

// Datos de conexión
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
    // Escapar datos para evitar inyección SQL básica
    $user = $conexion->real_escape_string($_POST['username']);
    $pass = $conexion->real_escape_string($_POST['password']);

    // Consulta
    $sql = "SELECT id, usuario FROM adminusuario WHERE usuario = '$user' AND clave = '$pass'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        // Extraemos los datos del usuario encontrado
        $datosUsuario = $resultado->fetch_assoc();

        // Guardamos en la sesión
        $_SESSION['usuario_id'] = $datosUsuario['id'];
        $_SESSION['nombre_usuario'] = $datosUsuario['usuario'];
        $_SESSION['autenticado'] = true;
        
        // Redirección exitosa
        header("Location: listaAlojamiento.php");
        exit();
    } else {
        // Error de credenciales
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location='adminLoginP.php';</script>";
    }
}
?>