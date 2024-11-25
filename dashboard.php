<?php
session_start();
include('./conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Consultar usuarios si el rol es admin
if ($role == 'admin') {
    $stmt = $conexion->query("SELECT * FROM usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si el usuario es regular, solo mostrar su propio perfil
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Consultar citas
$stmt_citas = $conexion->query("SELECT * FROM citas");
$citas = $stmt_citas->fetchAll(PDO::FETCH_ASSOC);

// Obtener estadísticas
$total_usuarios = count($usuarios);
$total_citas = count($citas);
$total_pendientes = count(array_filter($citas, fn($cita) => $cita['estado'] == 'pendiente'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        /* Estilos generales */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Contenedor del menú y el contenido */
        .container {
            display: flex;
            flex-direction: row;
        }

        /* Estilos para el menú lateral */
        .menu {
            width: 250px;
            height: 100vh;
            background-color: #333;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
            transition: width 0.3s;
            z-index: 1;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
        }

        .menu li {
            padding: 15px;
            text-align: left;
        }

        .menu li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .menu li a:hover {
            background-color: #575757;
        }

        /* Contenido principal */
        .content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }

        h1, h2 {
            color: #4a4a4a;
        }

        /* Estilo para las estadísticas */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .stats .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stats .card h3 {
            margin: 0;
            font-size: 24px;
            color: #4a4a4a;
        }

        .stats .card p {
            margin-top: 10px;
            font-size: 16px;
            color: #666;
        }

        /* Estilos para el botón de hamburguesa */
        .hamburger {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
            z-index: 2;
        }

        /* Mostrar icono hamburguesa solo en pantallas pequeñas */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .menu.closed {
                width: 60px;
            }

            .content {
                margin-left: 60px;
            }
        }
    </style>
</head>
<body>

    <!-- Botón de hamburguesa -->
    <div class="hamburger" onclick="toggleMenu()">☰</div>

    <!-- Menú lateral -->
    <div class="menu">
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="./funciones/gestionar_cita.php">Gestión de Citas</a></li>
            <li><a href="./funciones/editar_usuario.php">Gestión de Usuarios</a></li>
            <li><a href="./funciones/gestionar_precios.php">Gestión de Precios</a></li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Sección de estadísticas -->
        <h1>Bienvenido al Dashboard</h1>

        <div class="stats">
            <div class="card">
                <h3>Total de Usuarios</h3>
                <p><?php echo $total_usuarios; ?></p>
            </div>
            <div class="card">
                <h3>Total de Citas</h3>
                <p><?php echo $total_citas; ?></p>
            </div>
            <div class="card">
                <h3>Citas Pendientes</h3>
                <p><?php echo $total_pendientes; ?></p>
            </div>
        </div>

        <!-- Gestión de citas -->
        <h2>Gestión de citas</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td>
                            <?php
                                $stmt_usuario = $conexion->prepare("SELECT nombre FROM usuarios WHERE id = ?");
                                $stmt_usuario->execute([$cita['usuario_id']]);
                                $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
                                echo htmlspecialchars($usuario['nombre']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                        <td>
                            <?php if ($role == 'admin'): ?>
                                <a href="cita.php?id=<?php echo $cita['id']; ?>&accion=aceptar">Aceptar</a> |
                                <a href="cita.php?id=<?php echo $cita['id']; ?>&accion=cancelar">Cancelar</a>
                            <?php else: ?>
                                <?php if ($cita['estado'] == 'pendiente'): ?>
                                    <a href="cita.php?id=<?php echo $cita['id']; ?>&accion=cancelar">Cancelar cita</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Función para alternar el menú lateral
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            menu.classList.toggle('closed');
        }

        // Función para alternar el submenu
        function toggleSubmenu() {
            const submenu = event.target.nextElementSibling;
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        }
    </script>

</body>
</html>
