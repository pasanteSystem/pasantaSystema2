<?php
session_start();

// Si la variable 'autenticado' no existe o no es verdadera, lo mandamos al login
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: adminLoginP.php?error=acceso_denegado");
    exit();
}
?>