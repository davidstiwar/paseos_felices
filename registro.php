<?php

// Incluir el archivo de conexión
include('conexion.php');

// Verificar si el método de la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario y sanitizarlos
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? ''); 
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Validar campos obligatorios
    if (empty($nombre) || empty($correo) || empty($telefono) || empty($contrasena)) {
        die("Todos los campos son obligatorios.");
    }

    // Validar que el correo tenga el formato correcto
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico no válido.");
    }

    try {
        // Verificar si el correo ya está registrado
        $sql = "SELECT * FROM usuario WHERE correo = :correo";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':correo', $correo);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "El correo ya está registrado.";
        } else {
            // Encriptar la contraseña
            $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuario (nombre, correo, telefono, contrasena) VALUES (:nombre, :correo, :telefono, :contrasena)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':correo', $correo);
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':contrasena', $contrasena_encriptada);

            if ($stmt->execute()) {
                echo "Registro exitoso. Ahora puedes iniciar sesión.";
                // Redirigir al usuario a la página de inicio de sesión
                header("Location: login.php");
                exit();
            } else {
                echo "Error al registrar: " . $stmt->errorInfo()[2];
            }
        }
    } catch (Exception $e) {
        echo "Se produjo un error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
} else {
    echo "Método no permitido.";
}

?>