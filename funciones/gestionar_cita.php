<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'sis_venta';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

// Crear cita
if (isset($_POST['crear'])) {
    $usuario_id = $_POST['usuario_id'];
    $precio_id = $_POST['precio_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $estado = 'pendiente'; 

    // Validar que el usuario y precio existan
    $stmtUsuario = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE id = ?");
    $stmtUsuario->execute([$usuario_id]);
    $usuarioExiste = $stmtUsuario->fetchColumn();

    $stmtPrecio = $pdo->prepare("SELECT COUNT(*) FROM precios WHERE id = ?");
    $stmtPrecio->execute([$precio_id]);
    $precioExiste = $stmtPrecio->fetchColumn();

    if (!$usuarioExiste || !$precioExiste) {
        echo "<script>alert('Usuario o servicio no válido.');</script>";
    } else {
        // Comprobar si la fecha y hora de la cita son válidas (no pueden ser pasadas)
        $fecha_hora_cita = $fecha . ' ' . $hora;
        $fecha_hora_actual = date('Y-m-d H:i');

        if (strtotime($fecha_hora_cita) < strtotime($fecha_hora_actual)) {
            echo "<script>alert('No se puede crear una cita en una fecha y hora que ya han pasado.');</script>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO citas (usuario_id, precio_id, fecha, hora, estado) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $precio_id, $fecha, $hora, $estado]);
            echo "<script>alert('Cita creada exitosamente.');</script>";
        }
    }
}

// Eliminar cita
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Cita eliminada.');</script>";
}

// Confirmar cita
if (isset($_GET['confirmar'])) {
    $id = $_GET['confirmar'];
    $stmt = $pdo->prepare("UPDATE citas SET estado = 'confirmada' WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Cita confirmada.');</script>";
}

// Cancelar cita
if (isset($_GET['cancelar'])) {
    $id = $_GET['cancelar'];
    $stmt = $pdo->prepare("UPDATE citas SET estado = 'cancelada' WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Cita cancelada.');</script>";
}

// Editar cita
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $estado = $_POST['estado'];

    // Actualizar la cita
    $stmt = $pdo->prepare("UPDATE citas SET fecha = ?, hora = ?, estado = ? WHERE id = ?");
    $stmt->execute([$fecha, $hora, $estado, $id]);
    echo "<script>alert('Cita actualizada.');</script>";
}

// Obtener todas las citas con información adicional
$stmt = $pdo->query("
    SELECT 
        citas.id, 
        usuario.nombre AS nombre_cliente, 
        precios.nombre AS nombre_servicio, 
        citas.fecha, 
        citas.hora, 
        citas.estado
    FROM citas
    JOIN usuario ON citas.usuario_id = usuario.id
    JOIN precios ON citas.precio_id = precios.id
");
$citas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; margin: 0; padding: 0; }
        h1, h2 { color: #4a4a4a; text-align: center; }
        .container { width: 90%; max-width: 1200px; margin: 30px auto; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table thead { background-color: #1d2d50; color: #fff; }
        table td, table th { padding: 12px; border: 1px solid #e0e0e0; text-align: left; }
        button, a { padding: 10px 15px; border-radius: 5px; text-decoration: none; color: white; margin-right: 8px; }
        a.confirmar { background-color: #28a745; }
        a.cancelar { background-color: #dc3545; }
        a.eliminar { background-color: #6c757d; }
        button:hover, a:hover { opacity: 0.8; }
        input, select { padding: 5px 10px; width: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Citas</h1>
        
        <h2>Crear Cita</h2>
        <form action="citas.php" method="POST">
            <label for="usuario_id">Usuario:</label>
            <select name="usuario_id" required>
                <?php
                $usuarios = $pdo->query("SELECT id, nombre FROM usuario")->fetchAll();
                foreach ($usuarios as $usuario) {
                    echo "<option value='{$usuario['id']}'>{$usuario['nombre']}</option>";
                }
                ?>
            </select>
            <br>
            <label for="precio_id">Servicio:</label>
            <select name="precio_id" required>
                <?php
                $precios = $pdo->query("SELECT id, nombre FROM precios")->fetchAll();
                foreach ($precios as $precio) {
                    echo "<option value='{$precio['id']}'>{$precio['nombre']}</option>";
                }
                ?>
            </select>
            <br>
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required>
            <br>
            <label for="hora">Hora:</label>
            <input type="time" name="hora" required>
            <br>
            <button type="submit" name="crear">Crear Cita</button>
        </form>
        
        <hr>
        
        <h2>Lista de Citas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?= $cita['id'] ?></td>
                    <td><?= $cita['nombre_cliente'] ?></td>
                    <td><?= $cita['nombre_servicio'] ?></td>
                    <td>
                        <form action="citas.php" method="POST" style="display:inline;">
                            <input type="date" name="fecha" value="<?= $cita['fecha'] ?>" required>
                    </td>
                    <td>
                            <input type="time" name="hora" value="<?= $cita['hora'] ?>" required>
                    </td>
                    <td>
                        <select name="estado">
                            <option value="pendiente" <?= $cita['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="confirmada" <?= $cita['estado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                            <option value="cancelada" <?= $cita['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </td>
                    <td>
                            <input type="hidden" name="id" value="<?= $cita['id'] ?>">
                            <button type="submit" name="editar">Actualizar</button>
                        </form>
                        <?php if ($cita['estado'] == 'pendiente'): ?>
                            <a href="citas.php?confirmar=<?= $cita['id'] ?>" class="confirmar">Confirmar</a>
                            <a href="citas.php?cancelar=<?= $cita['id'] ?>" class="cancelar">Cancelar</a>
                        <?php endif; ?>
                        <a href="citas.php?eliminar=<?= $cita['id'] ?>" class="eliminar">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
