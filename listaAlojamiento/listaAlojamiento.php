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

 

// Obtener todas las sucursales para las pestañas
$sql_sucursales = "SELECT sucurs FROM sucursales ORDER BY sucurs ASC";
// Unimos la tabla registros con la tabla departamento de la otra base de datos
$sql = "SELECT r.*, d.depart 
        FROM registros r 
        LEFT JOIN departamentos d ON r.id_departamento = d.id_departamento 
        ORDER BY r.id DESC";

// 2. Ejecutamos la consulta una sola vez
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

// 3. Obtenemos las sucursales para el filtro
$sql_sucursales = "SELECT sucurs FROM sucursales ORDER BY sucurs ASC";
$res_sucursales = $conexion->query($sql_sucursales);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería de Carnets por Sucursal</title>
    <link rel="stylesheet" href="listaAlojamiento.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
</head>
<body>

    <header class="inventorio">
        <div class="encabezado">
            <h1>Inventario de Carnets</h1>
            <p>Filtrado por Sucursales</p>
        </div>
        
        <div class="headerBusqueda">
            <div class="busqueda">
                <input type="text" id="searchInput" placeholder="Buscar en esta sucursal...">
            </div>
        </div>
    </header>

    <div class="filter-container" style="text-align: center; margin: 5px 0;">
    <label for="sucursalSelect" style="font-weight: bold; margin-right: 10px;">Filtrar por Sucursal:</label>
    <select id="sucursalSelect" onchange="filtrarSucursal(this.value)" style="padding: 8px 15px; border-radius: 6px; border: 1px solid #cbd5e1; background-color: white; cursor: pointer; min-width: 200px;">
        <option value="todas">TODAS LAS SUCURSALES</option>
        <?php 
        // Reiniciamos el puntero del resultado de sucursales si ya se usó arriba
        $res_sucursales->data_seek(0); 
        while($suc = $res_sucursales->fetch_assoc()): 
        ?>
            <option value="<?php echo htmlspecialchars($suc['sucurs']); ?>">
                <?php echo htmlspecialchars($suc['sucurs']); ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

<div class="acciones-flotantes">
    <button class="btn-accion btn-limpiar" onclick="deseleccionarTodo()">🗑 Limpiar Todo</button>
    <button class="btn-imprimir-flotante" onclick="window.print();">🖨 Imprimir Seleccionados</button>
</div>

    <div class="contenedor-galeria">
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while($row = $resultado->fetch_assoc()): ?>
                
                <div class="bloque-completo-carnet" data-sucursal="<?php echo htmlspecialchars($row['sucursal']); ?>">
                    <div class="carnet" onclick="seleccionarParaImprimir(this)">
                        <div class="fondo-patron"></div>
                        <div class="header"></div>


                        
                        <div class="foto-box">
                                <?php if(!empty($row['foto'])): ?>
                                   <img src="../createCarnet/<?php echo $row['foto']; ?>" alt="Foto" class="img-click" style="width:100%; height:100%; object-fit: cover;">
                                <?php else: ?>
                                  <img src="img/default-user.png" alt="Sin foto" style="width:100px;; opacity: 0.5;">
                                <?php endif; ?>

                        </div>
                        <div class="datos-empleado">
                            <div class="campo2">
                                <span class="valor"><?php echo htmlspecialchars($row['nombre']); ?></span>
                            </div>
                            <div class="campo">
                                <span class="valor">V-<?php echo htmlspecialchars($row['cedula']); ?></span>
                            </div>
                            <div class="campo">
                                <span class="valor"><?php echo htmlspecialchars($row['fichaSPI']); ?></span>
                                <span class="valor"><?php echo htmlspecialchars($row['depart'] ?? 'Sin Departamento'); ?></span>
                            </div>
                        </div>
                        <div class="footerr">SERVICIO DE ALIMENTACIÓN</div>
                    </div>

                    <div class="nombre-etiqueta">
                        <?php echo htmlspecialchars($row['nombre']); ?>
                    </div>
                    <span class="text-fecha"><?php echo date("d/m/Y", strtotime($row['fecha_registro'])); ?></span>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay registros disponibles.</p>
        <?php endif; ?>
        <div id="noResults" style="display: none; text-align: center; padding: 20px; font-weight: bold; color: red;">
            No existen registros en esta sucursal.
        </div>
    </div>

    <script src="listaAlojamiento.js"></script>
    <script src="carnetTrasero2.js"></script>
</body>
</html>

 