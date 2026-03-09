<?php

require_once 'adminLogin/verificar_sesion.php';
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conexion = new mysqli($host, $user, $pass, $db, $port);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Personal - Sercoinfal</title>
    <link rel="stylesheet" href="registros.css">
    <link rel="stylesheet" href="imagenVista.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!--TITULOS DE ENCABEZADO CON BARRA DE BUSQUEDA-->
<div class="inventarioPrincipal">
    <header class="inventorio">
        <div class="encabezado">
            <h1>Inventario General de Carnetización</h1>
            <p>Listado de registros activos en el sistema</p>
        </div>
        
        <div class="headerBusqueda">
            <div class="busqueda">
                <input type="text" id="searchInput" placeholder="Buscar por nombre, ID o cédula...">
            </div>
            <button class="buscar"><i class="fa-solid fa-file-excel"></i></button>
        </div>
    </header>

    <!--TABLA DE REGISTROS ACTUALES-->
    <main class="inventory-content">
        <div class="tablacComp">
            <table>
                <thead>
                    <tr>
                        <th>Código Único</th>                        
                       <th>Ficha SPI</th>                     
                        <th>Nombre Completo</th>
                        <th>Cédula</th>
                        <th>Departamento</th>
                        <th>Cargo</th>
                        <th>Sucursal</th>
                        <th>Observaciones</th>
                        <th>Foto</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="inventoryBody">
                  <?php
    // 1. Consulta a la base de datos
    $sql = "SELECT r.*, d.depart 
        FROM registros r 
        LEFT JOIN departamentos d ON r.id_departamento = d.id_departamento 
        ORDER BY r.fecha_registro DESC";
    $resultado = $conexion->query($sql);
    $totalRegistros = $resultado->num_rows;

    $mesActual = "";

    // 2. Verificar si hay registros y recorrerlos
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $fechaTimestamp = strtotime($row['fecha_registro']);
        $mesRegistro = date("F Y", $fechaTimestamp); // Ejemplo: "February 2026"
        
        // Traducción rápida a español (Opcional)
        $mesesEspanol = [
            'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril',
            'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto',
            'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
        ];
        $mesNombre = strtr($mesRegistro, $mesesEspanol);

        // SI EL MES CAMBIA, imprimir una fila separadora
        if ($mesActual != $mesNombre) {
            $mesActual = $mesNombre;
            echo "<tr class='row-mes-separador'><td colspan='11'><strong>📅 $mesActual</strong></td></tr>";
        }
            ?>
            <tr>
                <td><span class="badge-id"><?php echo $row['ID']; ?></span></td>
                <td><?php echo $row['fichaSPI']; ?></td>
                <td><strong><?php echo $row['nombre']; ?></strong></td>
                <td><?php echo $row['cedula']; ?></td>
                <td><?php echo htmlspecialchars($row['depart'] ?? 'Sin Departamento'); ?></td>
                <td><?php echo $row['cargo']; ?></td>
                <td><?php echo $row['sucursal']; ?></td>
                <td><span class="textObser"><?php echo $row['observaciones']; ?></span></td>
                


               <td>
                <?php if(!empty($row['foto'])): ?>
                    <img src="createCarnet/<?php echo $row['foto']; ?>" alt="Foto" class="img-click" style="width:50px; height:50px; border-radius:50%; object-fit: cover; border: 1px solid #ddd;" onclick="ampliarImagen(this)">
                <?php else: ?>
                    <img src="img/default-user.png" alt="Sin foto" style="width:50px; border-radius:50%; opacity: 0.5;">
                <?php endif; ?>
                </td>       
                <td><span class="text-fecha"><?php echo date("d/m/Y", strtotime($row['fecha_registro'])); ?></span></td>
                
                <td class="actions-cell">
                    <button class="btn-icon edit" title="Editar" onclick="abrirEditar(<?php echo htmlspecialchars(json_encode($row)); ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button id="eliminar" class="btn-icon delete"  title="Eliminar" onclick="if(confirm('¿Estás seguro?')) { window.location.href='eliminar.php?ID=<?php echo $row['ID']; ?>'; }" style="color: red;"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='10'> No se encontraron registros</td></tr>";
    }
    ?>
    
                </tbody>
            </table>
        </div>
    </main>

    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="imgAmpliada">
    <div id="caption"></div>
</div>


</div>
    <!--FOOTER PORQUE SI-->
    <footer class="footer">
        <span class="totalRegistros">Total de registros: <strong id="totalCount"><?php echo $totalRegistros; ?></strong></span>
    </footer>


<!--MODAL PARA EDITAR LOS REGISTROSSSSSS-->
<div id="modalEditar" class="modal" style="display:none; background: rgba(0,0,0,0.5); position: fixed; top:0; left:0; width:100%; height:100%; z-index:1000;">
    <div style="background: white; width: 35%; min-width: 380px; margin: 30px auto; padding: 25px; border-radius: 12px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
        <span onclick="cerrarEditar()" style="position: absolute; right: 20px; top: 15px; cursor: pointer; font-size: 24px; color: #64748b;">&times;</span>
        <h3 style="margin-top: 0; color: #0f172a;">Editar Registro</h3>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 15px 0;">
        
        <form action="actualizar.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id"> <label style="display:block; margin-bottom: 5px; font-weight: 600; font-size: 0.85rem;">Nombre Completo:</label>
            <input type="text" name="nombre" id="edit_nombre" required style="width:100%; margin-bottom:15px; padding:10px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box;">

            <label style="display:block; margin-bottom: 5px; font-weight: 600; font-size: 0.85rem;">Actualizar Foto:</label>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
        <img id="edit_preview" src="" style="width:50px; height:50px; border-radius:50%; object-fit: cover; border: 1px solid #ddd; display:none;">
        <input type="file" name="nueva_foto" id="edit_foto" accept="image/*" style="font-size: 0.8rem;">
    </div>


            
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display:block; margin-bottom: 5px; font-weight: 600; font-size: 0.85rem;">Cédula:</label>
                    <input type="text" name="cedula" id="edit_cedula" style="width:100%; padding:10px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box;">
                </div>
                <div style="flex: 1;">
                    <label style="display:block; margin-bottom: 5px; font-weight: 600; font-size: 0.85rem;">Ficha SPI:</label>
                    <input type="text" name="ficha" id="edit_ficha" style="width:100%; padding:10px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box;">
                </div>
            </div>

            <label>Departamento:</label>
<select id="edit_depto" name="departamento">
    <?php 
    $resultado2 = $conexion->query("SELECT id_departamento, depart FROM departamentos ORDER BY depart ASC");
    if ($resultado2 && $resultado2->num_rows > 0) {
        while($row_dep = $resultado2->fetch_assoc()) {
            // El value DEBE ser el ID
            echo "<option value='".$row_dep['id_departamento']."'>".$row_dep['depart']."</option>";
        }
    }
    ?>
</select>

            <label style="display:block; margin-bottom: 5px; font-weight: 600; font-size: 0.85rem;">Sucursal:</label>
            <select id="edit_sucursal" name="sucursal" style="width:100%; margin-bottom:15px; padding:10px; border: 1px solid #e2e8f0; border-radius: 6px; background: white;">
                <option value="">Seleccione Departamento...</option>
                <?php 
                $sql3 = "SELECT sucurs FROM sucursales ORDER BY sucurs ASC";
                $resultado3 = $conexion->query($sql3);
                if ($resultado3 && $resultado3->num_rows > 0) {
                    while($row_suc = $resultado3->fetch_assoc()) {
                        echo "<option value='".$row_suc['sucurs']."'>".$row_suc['sucurs']."</option>";
                    }
                }
                ?>
            </select>

            <label style="display:block; margin-bottom: 5px; font-weight: 600; font-size: 0.85rem;">Cargo:</label>
            <input type="text" name="cargo" id="edit_cargo" style="width:100%; margin-bottom:15px; padding:10px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box;">

            <div style="text-align: right; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                <button type="button" onclick="cerrarEditar()" style="padding: 10px 20px; border: 1px solid #e2e8f0; background:#f8fafc; border-radius:6px; cursor:pointer;">Cancelar</button>
                <button type="submit" style="padding: 10px 25px; border:none; background:#2563eb; color:white; border-radius:6px; font-weight:600; cursor:pointer; margin-left: 10px;">Actualizar Datos</button>
            </div>
        </form>
    </div>
</div>
    <script src="cerrarRegistro.js"></script>
    <script src="registro.js"></script>
    <script src="imagenVista.js"></script>
</body>
</html>

 
