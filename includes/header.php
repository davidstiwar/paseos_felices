<header>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo"><img src="./img/logo_nav_redondo.png" alt="logo"></div>
            <button class="hamburger" aria-label="Toggle menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <ul class="nav-links">
                <li><a href="./index.php">Inicio</a></li>
                <li><a href="./cita.php">Citas</a></li>
                <li><a href="#">Acerca de</a></li>
                <li><a href="#">Contacto</a></li>

                <!-- Verificar si hay una sesión iniciada -->
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li>
                        <span>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?></span>
                    </li>
                    <li>
                        <form action="logout.php" method="POST" style="display:inline;">
                            <button class="b_cerrar" type="submit">Cerrar sesión</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
