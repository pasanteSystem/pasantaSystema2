<?php
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conexion = new mysqli($host, $user, $pass, $db, $port);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

echo "Database: $db\n";
$result = $conexion->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $table = $row[0];
    echo "Table: $table\n";
    $columns = $conexion->query("SHOW COLUMNS FROM $table");
    while ($col = $columns->fetch_assoc()) {
        echo "  - Column: " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
}
$conexion->close();
?>
