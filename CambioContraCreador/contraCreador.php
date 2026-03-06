<?php
session_start();

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conexion = new mysqli($host, $user, $pass, $db, $port);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


$mensaje = "";

// --- LÓGICA DE REGISTRO ---
if (isset($_POST['registrar'])) {
    $usuario = $conexion->real_escape_string($_POST['usuario']);
    $password = $_POST['password'];
    
    // ENCRIPTACIÓN: Creamos el hash seguro
    $pass_encriptada = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO adminusuario (usuario, clave) VALUES ('$usuario', '$pass_encriptada')";
    
    if ($conexion->query($sql)) {
        $mensaje = "<p style='color: green;'>Usuario registrado con éxito. ¡Ya puedes loguearte!</p>";
    } else {
        $mensaje = "<p style='color: red;'>Error: El usuario ya existe o hubo un fallo técnico.</p>";
    }
}

// --- LÓGICA DE LOGIN ---
if (isset($_POST['login'])) {
    $usuario = $conexion->real_escape_string($_POST['usuario']);
    $password = $_POST['password'];

    $sql = "SELECT clave FROM adminusuario WHERE usuario = '$usuario'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        // VERIFICACIÓN: Comparamos el texto plano con el hash de la DB
        if (password_verify($password, $datos['clave'])) {
            $_SESSION['usuario'] = $usuario;
            header("Location: dashboard.php"); // Cambia esto a tu página principal
            exit();
        } else {
            $mensaje = "<p style='color: red;'>Contraseña incorrecta.</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>El usuario no existe.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #1a3a6d; margin-top: 0; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-bottom: 5px; }
        .btn-login { background: #2563eb; color: white; }
        .btn-reg { background: #10b981; color: white; }
        .mensaje { text-align: center; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="card">
    <h2>Sercoinfal Auth</h2>
    <div class="mensaje"><?php echo $mensaje; ?></div>
    
    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        
        <button type="submit" name="login" class="btn-login">Iniciar Sesión</button>
        <button type="submit" name="registrar" class="btn-reg">Registrar Nuevo</button>
    </form>
</div>

</body>
</html>