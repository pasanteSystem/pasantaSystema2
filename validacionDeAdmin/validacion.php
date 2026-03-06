<?php

header('Content-Type: application/json');

// Configuración de conexión
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conexion = new mysqli($host, $user, $pass, $db, $port);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'mensaje' => 'Error de conexión a la base de datos']);
    exit;
}

// IMPORTANTE: Los nombres dentro de [''] deben coincidir con datos.append en el JS
$u = $_POST['usuario'] ?? '';
$p = $_POST['password'] ?? '';

if (empty($u) || empty($p)) {
    echo json_encode(['success' => false, 'mensaje' => 'Campos vacíos en el servidor']);
    exit;
}

// Consulta usando las columnas: id, user, passwordd
$sql = "SELECT * FROM creadoradmin WHERE user = ? AND passwordd = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $u, $p);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
}

$stmt->close();
$conexion->close();
?>