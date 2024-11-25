<?php
session_start();

// Incluir el archivo de conexión
include('conexion.php');  // Asegúrate de que el archivo conexion.php esté en la misma carpeta o ajusta la ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    if (!empty($nombre) && !empty($contrasena)) {
        // Verifica si la conexión a la base de datos está establecida
        if (!isset($conexion)) {
            die("Error: La variable de conexión a la base de datos no está definida.");
        }

        try {
            // Prepara la consulta para buscar al usuario
            $stmt = $conexion->prepare("SELECT * FROM usuario WHERE nombre = :nombre");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica si el usuario existe y si la contraseña es correcta
            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario'] = $usuario['nombre'];
                $_SESSION['role'] = $usuario['role'];

                // Redirige según el rol del usuario
                if ($usuario['role'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                echo "Usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>
