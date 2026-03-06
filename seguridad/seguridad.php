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



// 1. Obtener todos los usuarios para el desplegable
$usuarios_res = $conexion->query("SELECT id, usuario FROM adminusuario");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $id_usuario = $_POST['id_usuario']; // ID del usuario seleccionado
    $anteriorCont = $_POST['old_pass'];
    $nueva_pass = $_POST['new_pass'];
    $confirmar = $_POST['confirm_pass'];

    // 2. Buscamos el hash del usuario seleccionado
    $consulta = "SELECT clave FROM adminusuario WHERE id = '$id_usuario'";
    $resultado = mysqli_query($conexion, $consulta);
    $fila = mysqli_fetch_assoc($resultado);
    $hashGuardado = $fila['clave']; 

    // 3. Verificación de la contraseña actual
    if (password_verify($anteriorCont, $hashGuardado)) {
        
        if (!empty($nueva_pass) && $nueva_pass === $confirmar) {
            
            $nueva_hash = password_hash($nueva_pass, PASSWORD_DEFAULT);
            $nueva_pass_limpia = mysqli_real_escape_string($conexion, $nueva_hash);

            // 4. Actualizamos el registro del usuario específico
            $cambiarDatos = "UPDATE adminusuario SET clave = '$nueva_pass_limpia' WHERE id = '$id_usuario'";
            $ejecutarActualizar = mysqli_query($conexion, $cambiarDatos);

            if($ejecutarActualizar){
                echo "<script>alert('Contraseña de usuario actualizada con éxito'); window.location='seguridad.php';</script>";
            } else {
                echo "<script>alert('Error técnico al actualizar');</script>";
            }
        } else {
            echo "<script>alert('La nueva contraseña y la confirmación no coinciden');</script>";
        }
    } else {
        echo "<script>alert('La contraseña actual es incorrecta');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="seguridad.css">
    <title>Seguridad - Gestión de Usuarios</title>
</head>
<body>
    <div class="container-password">
        <div class="password-card">
            <h3>Cambio de Contraseña de Usuario</h3>
            <form action="" method="POST">
                
                <div class="input-group">
                    <label for="id_usuario">Seleccionar Usuario</label>
                    <select name="id_usuario" id="id_usuario" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="">-- Seleccione un usuario --</option>
                        <?php while($u = $usuarios_res->fetch_assoc()): ?>
                            <option value="<?php echo $u['id']; ?>"><?php echo $u['usuario']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label for="old_pass">Contraseña Actual del Administrador</label>
                    <input type="password" id="old_pass" name="old_pass" required placeholder="Escribe tu clave actual">
                </div>

                <hr style="margin: 20px 0; border: 0; border-top: 1px dashed #ccc;">

                <div class="input-group">
                    <label for="new_pass">Nueva Contraseña para el Usuario</label>
                    <input type="password" id="new_pass" name="new_pass" required>
                </div>

                <div class="input-group">
                    <label for="confirm_pass">Confirmar Nueva Contraseña</label>
                    <input type="password" id="confirm_pass" name="confirm_pass" required>
                </div>

                <div class="acciones">
                    <button type="submit" name="actualizar" class="botonGuardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>