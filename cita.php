<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}
include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Citas</title>
  <!-- Bootstrap CSS v5.2.1 -->
  <link rel="stylesheet" href="./css/estilo.css">


  <style>
/* Estilos generales */
body {
  font-family: 'Arial', sans-serif;
  background-color: #f9f9f9;
  color: #333;
  margin: 0;
  padding: 0;
}

h1, h2, h3 {
  color: #4a4a4a;
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

/* Formulario para crear cita */
.contenedor {
  display: flex;
  gap: 20px;
  justify-content: space-between;
  flex-wrap: wrap;
  margin-bottom: 30px;
}

/* Crear cita */
.crear-cita {
  flex: 1;
  padding: 20px;
  border: 1px solid #ccc;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  background-color: #ffffff;
}

.crear-cita h1 {
  text-align: center;
  color: #333;
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

table td,
table th {
  padding: 12px;
  border: 1px solid #e0e0e0;
}

/* Botones */
button,
a {
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
  background-color: #000; /* Azul suave */
}

a.edit:hover {
  background-color: #0056b3; /* Azul más oscuro */
}

a.delete {
  background-color: #dc3545; /* Rojo */
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

form input,
form select,
form textarea {
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

  button,
  a {
    font-size: 14px;
    padding: 10px 15px;
  }

  /* Ajustar tamaño de campos de formulario */
  form input,
  form select,
  form textarea {
    font-size: 14px;
  }
}

/* Estilos para la tabla de precios */
.tabla_precios {
  flex: 1;
  padding: 20px;
  border: 1px solid #ccc;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  background-color: #ffffff;
}

.table_precios table {
  width: 100%;
  border-collapse: collapse;
}

.table_precios th,
.table_precios td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
  </style>
</head>

<body>
  <?php
  include './includes/header.php';
  ?>

  <!-- Formulario para crear cita -->
  <div class="contenedor">
    <div class="crear-cita">
      <h1>Crear Cita</h1>
      <form action="citas.php" method="POST" onsubmit="return validarCita()">
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required>
        <br><br>

        <label for="hora">Hora:</label>
        <input type="time" name="hora" id="hora" required>
        <br><br>

        <label for="descripcion">Descripción personalizada:</label>
        <input type="text" name="descripcion" id="descripcion" required>
        <br><br>

        <label for="servicio">Selecciona el Servicio:</label>
        <select name="servicio" id="servicio" required>
          <option value="">Selecciona un servicio</option>
          <?php
          $sql = "SELECT * FROM precios";
          $result = $conexion->query($sql);
          if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
            }
          } else {
            echo "<option value=''>No hay servicios disponibles</option>";
          }
          ?>
        </select>
        <br><br>

        <input type="submit" name="crear" value="Crear Cita">
      </form>
    </div>

    <div class="tabla_precios">
      <h1>Tabla de Precios</h1>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Precio Base</th>
            <th>Tasa IVA (%)</th>
            <th>IVA</th>
            <th>Precio Total</th>
            <th>Descripción</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM precios";
          $stmt = $conexion->query($sql);
          if ($stmt && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
              $iva_calculado = $row['precio'] * ($row['iva'] / 100);
              $precio_total = $row['precio'] + $iva_calculado;
          ?>
              <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo number_format($row['precio'], 2, ',', '.'); ?></td>
                <td><?php echo $row['iva']; ?>%</td>
                <td><?php echo number_format($iva_calculado, 2, ',', '.'); ?></td>
                <td><?php echo number_format($precio_total, 2, ',', '.'); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
              </tr>
          <?php
            endwhile;
          } else {
            echo "<tr><td colspan='6'>No hay precios disponibles</td></tr>";
          }
          ?>
        </tbody>
      </table>

      <div class="container">
        <h1>Tus Citas</h1>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descripción</th>
                    <th>Servicio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Comprobar que la sesión contiene el usuario y su id
                if (isset($_SESSION['usuario']['id'])) {
                    $usuario_id = $_SESSION['usuario']['id'];  // Acceder al ID del usuario
                } else {
                    echo "<tr><td colspan='5'>No se encontró el ID del usuario en la sesión.</td></tr>";
                    exit;
                }

                // Preparar la consulta para obtener las citas
                $sql = "SELECT c.id, c.fecha, c.hora, c.descripcion, p.nombre AS servicio
                FROM citas c
                JOIN precios p ON c.servicio_id = p.id
                WHERE c.usuario_id = :usuario_id";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id]);
        
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                echo "<td>" . htmlspecialchars($row['hora']) . "</td>";
                echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['servicio']) . "</td>";
                echo "<td>
                        <a href='cancelar_cita.php?id=" . $row['id'] . "' class='cancelar'>Cancelar</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No tienes citas programadas.</td></tr>";
        }
        
        // Agrega una línea para depurar si no se están encontrando citas
        echo "<!-- Cantidad de filas encontradas: " . $stmt->rowCount() . " -->";
                
                ?>
            </tbody>
        </table>
    </div>

    </div>
  </div>


  <?php
  include './includes/footer.php';
  ?>
</body>

</html>