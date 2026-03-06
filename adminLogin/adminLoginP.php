<?php
session_start();

// 1. TODA LA LÓGICA ANTES DEL HTML
if (isset($_POST['listo'])) {
    // Usamos los nombres exactos que viste en el panel de Railway
    $host = getenv('MYSQLHOST'); 
    $user = getenv('MYSQLUSER');
    $pass = getenv('MYSQLPASSWORD');
    $db   = getenv('MYSQLDATABASE');
    $port = getenv('MYSQLPORT');

    // IMPORTANTE: El orden de los argumentos para mysqli
    // 1. Host, 2. User, 3. Pass, 4. DB, 5. Port
    $conexion = new mysqli($host, $user, $pass, $db, $port);

    if ($conexion->connect_error) {
        // Esto nos dirá exactamente qué falló si sigue el error
        die("Error de conexión (" . $conexion->connect_errno . "): " . $conexion->connect_error);
    }

    $usuario = $conexion->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, clave FROM adminusuario WHERE usuario = '$usuario'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        
        // VERIFICACIÓN DE ENCRIPTACIÓN
        if (password_verify($password, $datos['clave'])) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['autenticado'] = true; // Importante para tu verificar_sesion.php
            
            header("Location: ../sercoInterfaz.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El usuario no existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sercoinfal</title>
    <link rel="stylesheet" href="adminLogin.css">
</head>
<body>
    <div class="login-container">
        <form id="loginForm" action="" method="POST">
            <h2>Iniciar Sesión</h2>
            
            <?php if(isset($error)): ?>
                <p style="color: #ff4d4d; text-align: center; font-weight: bold;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="input-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required placeholder="Ingresa tu usuario">
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button name="listo" type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</body>
</html>