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


// 2. PROCESAR INSERCIÓN DE DEPARTAMENTO
if (isset($_POST['enviar'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['agregar']);
    if (!empty($nombre)) {
        // Asumiendo que moviste la tabla 'departamento' a 'registroscarnet'
        $sql = "INSERT INTO departamentos (depart) VALUES ('$nombre')";
        mysqli_query($conexion, $sql);
        header("Location: datos.php");
        exit();
    }
}

// 3. PROCESAR INSERCIÓN DE SUCURSAL
if (isset($_POST['enviar2'])) {
    $nombre2 = mysqli_real_escape_string($conexion, $_POST['agregar2']);
    if (!empty($nombre2)) {
        $sql = "INSERT INTO sucursales (sucurs) VALUES ('$nombre2')";
        mysqli_query($conexion, $sql);
        header("Location: datos.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Datos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="datos.css">
</head>
<body>

    <div class="container2">
        
        <div class="columna-datos">
            <div class="container">
                <form method="POST" action="">
                    <label><strong>Nuevo Departamento:</strong></label>
                    <input type="text" name="agregar" placeholder="ingrese nuevo departamento" required>
                    <button name="enviar" type="submit">Agregar Departamento</button>
                </form>
            </div>

            <div class="container">
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Departamentos Registrados</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $res_dep = mysqli_query($conexion, "SELECT * FROM departamentos");
                        while($row = mysqli_fetch_assoc($res_dep)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['depart']); ?></td>
                                <td class="txt-center">
                                    <button class="btn-icon delete" onclick="if(confirm('¿Eliminar departamento?')) { window.location.href='eliminarDatos.php?id=<?php echo $row['id_departamento']; ?>'; }">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="columna-datos">
            <div class="container">
                <form method="POST" action="">
                    <label><strong>Nueva Sucursal:</strong></label>
                    <input type="text" name="agregar2" placeholder="ingrese nueva sucursal" required>
                    <button name="enviar2" type="submit">Agregar Sucursal</button>
                </form>
            </div>

            <div class="container">
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Sucursales Registradas</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $res_suc = mysqli_query($conexion, "SELECT * FROM sucursales");
                        while($row = mysqli_fetch_assoc($res_suc)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['sucurs']); ?></td>
                                <td class="txt-center">
                                    <button class="btn-icon delete" onclick="if(confirm('¿Eliminar sucursal?')) { window.location.href='eliminarDatos2.php?id=<?php echo $row['id']; ?>'; }">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="datos.js"></script>
</body>
</html>

<?php
$servidor = "localhost";
?>