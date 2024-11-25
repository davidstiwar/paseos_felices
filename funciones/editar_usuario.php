<?php
include('../conexion.php');

// Inicializar variables
$nombre = $correo = $telefono = $mensaje = "";
$contrasena = "";
$accion = "crear";

// Crear usuario
if (isset($_POST['crear'])) {
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $telefono = $_POST['telefono'];
  $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);

  $sql = "INSERT INTO usuario (nombre, correo, contrasena, telefono) VALUES (?, ?, ?, ?)";
  $stmt = $conexion->prepare($sql);
  $stmt->execute([$nombre, $correo, $contrasena, $telefono]);
  $mensaje = "Usuario creado correctamente.";
}

// Editar usuario
if (isset($_POST['editar'])) {
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $telefono = $_POST['telefono'];

  if (!empty($_POST['contrasena'])) {
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    $sql = "UPDATE usuario SET nombre = ?, correo = ?, contrasena = ?, telefono = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$nombre, $correo, $contrasena, $telefono, $id]);
  } else {
    $sql = "UPDATE usuario SET nombre = ?, correo = ?, telefono = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$nombre, $correo, $telefono, $id]);
  }

  $mensaje = "Usuario actualizado correctamente.";
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
  $id = $_GET['eliminar'];
  $sql = "DELETE FROM usuario WHERE id = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->execute([$id]);
  $mensaje = "Usuario eliminado correctamente.";
}

// Obtener usuario para editar
if (isset($_GET['editar'])) {
  $id = $_GET['editar'];
  $sql = "SELECT * FROM usuario WHERE id = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->execute([$id]);
  $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($usuario) {
    $nombre = $usuario['nombre'];
    $correo = $usuario['correo'];
    $telefono = $usuario['telefono'];
    $accion = "actualizar";
  }
}

// Obtener todos los usuarios
$sql = "SELECT * FROM usuario";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Usuarios</title>
  <style>
  /* Estilos generales */
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f9f9; /* Fondo claro */
    color: #333;
    margin: 0;
    padding: 0;
  }

  h1, h2, h3 {
    color: #4a4a4a; /* Gris oscuro */
    text-align: center;
  }

  /* Contenedor principal */
  .container {
    width: 90%;
    max-width: 1200px;
    margin: 30px auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }

  /* Tablas */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    font-size: 15px;
  }

  table thead {
    background-color: #1d2d50; /* Azul oscuro */
    color: #fff;
  }

  table thead th {
    padding: 12px;
    text-align: left;
  }

  table tbody tr:nth-child(even) {
    background-color: #f4f6f9; /* Gris suave */
  }

  table tbody tr:hover {
    background-color: #e2e8f0; /* Azul muy claro */
  }

  table td, table th {
    padding: 12px;
    border: 1px solid #e0e0e0;
  }

  /* Botones */
  button, a {
    display: inline-block;
    padding: 12px 20px;
    font-size: 16px;
    text-decoration: none;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  button {
    background-color: #6c757d; /* Gris oscuro */
  }

  button:hover {
    background-color: #5a636b; /* Gris más oscuro */
  }

  a.edit {
    background-color: black; /* Azul suave */
  }

  a.edit:hover {
    background-color: #0056b3; /* Azul más oscuro */
  }

  a.delete {
    background-color: black; /* Rojo */
  }

  a.delete:hover {
    background-color: #c82333; /* Rojo más oscuro */
  }

  /* Formularios */
  form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
  }

  form label {
    font-weight: bold;
    color: #4a4a4a;
  }

  form input, form select {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    background-color: #f7f7f7;
  }

  form input[type="submit"] {
    background-color: #28a745; /* Verde */
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  form input[type="submit"]:hover {
    background-color: #218838; /* Verde más oscuro */
  }

  /* Header */
  header {
    background-color: #1d2d50; /* Azul oscuro */
    color: #fff;
    padding: 20px 30px;
    text-align: center;
    border-radius: 12px 12px 0 0;
    margin-bottom: 30px;
  }

  /* Footer */
  footer {
    margin-top: 30px;
    text-align: center;
    color: #868e96; /* Gris claro */
    font-size: 13px;
  }

  /* Botones de acción en tablas */
  .action-buttons a {
    padding: 10px 15px;
    font-size: 13px;
    text-decoration: none;
    color: white;
    margin-right: 8px;
    border-radius: 5px;
  }

  .action-buttons a.aceptar {
    background-color: #28a745; /* Verde */
  }

  .action-buttons a.cancelar {
    background-color: #dc3545; /* Rojo */
  }

  .action-buttons a:hover {
    opacity: 0.8;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .container {
      padding: 20px;
    }

    table {
      font-size: 14px;
    }

    button, a {
      font-size: 14px;
      padding: 10px 15px;
    }
  }
</style>

</head>

<body>
  <h1>Gestión de Usuarios</h1>

  <?php if ($mensaje): ?>
    <p style="color: green;"><?php echo $mensaje; ?></p>
  <?php endif; ?>

  <form method="post">
    <input type="hidden" name="id" value="<?php echo isset($_GET['editar']) ? $_GET['editar'] : ''; ?>">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" required><br>

    <label for="correo">Correo:</label>
    <input type="email" name="correo" id="correo" value="<?php echo $correo; ?>" required><br>

    <label for="telefono">Teléfono:</label>
    <input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" required><br>

    <label for="contrasena">Contraseña:</label>
    <input type="password" name="contrasena" id="contrasena" <?php echo $accion === "crear" ? "required" : ""; ?>><br>

    <button type="submit" name="<?php echo $accion; ?>">
      <?php echo ucfirst($accion); ?> Usuario
    </button>
  </form>

  <h2>Lista de Usuarios</h2>
  <table border="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuario as $usuario): ?>
        <tr>
          <td><?php echo $usuario['id']; ?></td>
          <td><?php echo $usuario['nombre']; ?></td>
          <td><?php echo $usuario['correo']; ?></td>
          <td><?php echo $usuario['telefono']; ?></td>
          <td>
            <a href="?editar=<?php echo $usuario['id']; ?>">Editar</a> |
            <a href="?eliminar=<?php echo $usuario['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>