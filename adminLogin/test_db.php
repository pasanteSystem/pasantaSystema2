<?php
// adminLogin/test_db.php
header('Content-Type: text/html; charset=utf-8');

echo "<h2>🔍 Verificación de Entorno Railway</h2>";

$vars = ['MYSQLHOST', 'MYSQLUSER', 'MYSQLPASSWORD', 'MYSQLDATABASE', 'MYSQLPORT'];
foreach ($vars as $v) {
    $valor = getenv($v);
    echo "<b>$v:</b> " . ($valor ? "✅ Detectada" : "❌ <span style='color:red'>VACÍA</span>") . "<br>";
}

echo "<h3>🚀 Probando Conexión...</h3>";

$conexion = @new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    getenv('MYSQLPORT')
);

if ($conexion->connect_error) {
    echo "<div style='background:#ffeded; padding:10px; border:1px solid red;'>";
    echo "<b>Error de conexión:</b> " . $conexion->connect_error . "<br>";
    echo "<b>Código de error:</b> " . $conexion->connect_errno;
    echo "</div>";
} else {
    echo "<div style='background:#edffed; padding:10px; border:1px solid green;'>";
    echo "✅ ¡Conexión exitosa a la base de datos!";
    echo "</div>";
}
?>