<?php
session_start(); // Iniciar la sesión
session_unset(); // Destruir todas las variables de sesión
session_destroy(); // Destruir la sesión
// Redirigir a la página de inicio o a donde desees
header("Location: index.php");
exit();
?>