<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="./img/favicon_io/favicon-32x32.png" sizes="32x32">
    <!-- Estilos -->
    <link rel="stylesheet" href="./css/login.css">
    <title>Formulario Login y Registro de Usuarios</title>
</head>

<body>
    <?php
    include './login-l.php';
    ?>
    <!-- Formularios -->
    <div class="contenedor-formularios">
        <!-- Links de los formularios -->
        <ul class="contenedor-tabs">
            <li class="tab tab-segunda active"><a href="#iniciar-sesion">Iniciar Sesión</a></li>
            <li class="tab tab-primera"><a href="#registrarse">Registrarse</a></li>
        </ul>


        <!-- Contenido de los Formularios -->
        <div class="contenido-tab">
            <!-- Iniciar Sesion -->
            <div id="iniciar-sesion">
                <h1>Iniciar Sesión</h1>
                <form action="./login-l.php" method="POST">
                    <div class="contenedor-input">
                        <label>
                            Nombre <span class="req">*</span>
                        </label>
                        <input type="text" name="nombre" required>
                    </div>

                    <div class="contenedor-input">
                        <label>
                            Contraseña <span class="req">*</span>
                        </label>
                        <input type="password" name="contrasena" required>
                    </div>
                    <input type="submit" class="button button-block" value="Iniciar Sesión">
                </form>

            </div>

            <!-- Registrarse -->
            <div id="registrarse">
                <h1>Registrarse</h1>
                <form action="./registro.php" method="POST">
                    <div class="fila-arriba">
                        <div class="contenedor-input">
                            <label for="nombre">Nombre <span class="req">*</span></label>
                            <input type="text" id="nombre" name="nombre" required aria-label="Nombre">
                        </div>
                    </div>
                    <div class="contenedor-input">
                        <label for="correo-registro">Correo <span class="req">*</span></label>
                        <input type="email" id="correo-registro" name="correo" required aria-label="Correo">
                    </div>
                    <div class="contenedor-input">
                        <label for="contrasena-registro">Contraseña <span class="req">*</span></label>
                        <input type="password" id="contrasena-registro" name="contrasena" required aria-label="Contraseña">
                    </div>
                    <div class="contenedor-input">
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono" required pattern="[0-9]{10,15}" aria-label="Teléfono">
                    </div>
                    <input type="submit" class="button button-block" value="Registrarse">
                </form>
            </div>
        </div>
    </div>

    <script src="./js/jquery.js"></script>
    <script src="./js/login.js"></script>
</body>

</html>