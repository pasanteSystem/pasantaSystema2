<?php
session_start(); // Localizamos la sesión actual
session_unset(); // Borramos todas las variables de sesión
session_destroy(); // Destruimos la sesión por completo

// Redirigimos al login
header("Location: adminLoginP.php");
exit();
?>