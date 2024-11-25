<?php
// Incluir el archivo de conexión
include('./conexion.php');

// Determinar qué acción realizar según los parámetros
$action = isset($_GET['action']) ? $_GET['action'] : 'solicitar';

if ($action == 'solicitar' && $_SERVER["REQUEST_METHOD"] == "POST") {
    // *** SOLICITAR RESTABLECIMIENTO ***
    $email = trim($_POST['email']);

    // Verificar si el correo existe
    $sql = "SELECT id FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['id'];

        // Generar token único
        $token = bin2hex(random_bytes(32));
        $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar token y expiración en la base de datos
        $sql = "INSERT INTO restablecimiento_contrasena (usuario_id, token, expiracion) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $usuario_id, $token, $expiracion);
        $stmt->execute();

        // Simular envío de correo
        $enlace = "http://tu-sitio.com/restablecer_contrasena.php?action=restablecer&token=" . $token;
        echo "Enlace de restablecimiento enviado a tu correo: <a href='$enlace'>$enlace</a>";
    } else {
        echo "El correo electrónico no está registrado.";
    }
} elseif ($action == 'restablecer' && isset($_GET['token'])) {
    // *** MOSTRAR FORMULARIO PARA NUEVA CONTRASEÑA ***
    $token = $_GET['token'];
    ?>

    <!-- Formulario para cambiar la contraseña -->
    <form method="POST" action="restablecer_contrasena.php?action=actualizar">
        <h2>Crear Nueva Contraseña</h2>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="password">Nueva Contraseña</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>

    <?php
} elseif ($action == 'actualizar' && $_SERVER["REQUEST_METHOD"] == "POST") {
    // *** ACTUALIZAR CONTRASEÑA ***
    $token = trim($_POST['token']);
    $nueva_contrasena = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    // Verificar si el token es válido y no ha expirado
    $sql = "SELECT usuario_id FROM restablecimiento_contrasena WHERE token = ? AND expiracion > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['usuario_id'];

        // Actualizar la contraseña del usuario
        $sql = "UPDATE usuario SET contrasena = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nueva_contrasena, $usuario_id);
        $stmt->execute();

        // Eliminar el token
        $sql = "DELETE FROM restablecimiento_contrasena WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "Contraseña actualizada exitosamente.";
    } else {
        echo "Token inválido o expirado.";
    }
} else {
    // *** FORMULARIO DE SOLICITUD ***
    ?>

    <!-- Formulario para solicitar restablecimiento -->
    <form method="POST" action="restablecer_contrasena.php?action=solicitar">
        <h2>Restablecer Contraseña</h2>
        <label for="email">Correo Electrónico</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Enviar Enlace</button>
    </form>

    <?php
}

// Cerrar la conexión
$conn->close();
?>
