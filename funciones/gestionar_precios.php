<?php
// Incluir el archivo de conexión
include '../conexion.php';

try {
    // Verificar conexión
    if ($conexion === null) {
        throw new Exception("No se pudo establecer la conexión a la base de datos.");
    }
} catch (Exception $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}

// Inicializar variables
$id = null;
$fila = [
    'nombre' => '',
    'precio' => '',
    'descripcion' => '',
    'iva' => 19,
    'beneficios' => ''
];

// Comprobar si hay un ID en la URL (modo edición)
if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Asegurar que el ID sea un entero
    $sql = "SELECT * FROM precios WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        die("No se encontró el registro.");
    }
}

// Procesar formulario al enviar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $precio = (float) $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $iva = (float) $_POST['iva'];
    $beneficios = $_POST['beneficios'];

    // Validar campos obligatorios
    if (empty($nombre) || empty($precio) || empty($descripcion)) {
        die("Los campos nombre, precio y descripción son obligatorios.");
    }

    // Calcular IVA y precio total
    $iva_decimal = $iva / 100;
    $iva_calculado = $precio * $iva_decimal;
    $precio_total = $precio + $iva_calculado;

    if ($id) {
        // Actualizar registro
        $sql = "UPDATE precios 
                SET nombre = :nombre, precio = :precio, descripcion = :descripcion, 
                    iva = :iva, precio_total = :precio_total, beneficios = :beneficios 
                WHERE id = :id";
    } else {
        // Insertar nuevo registro
        $sql = "INSERT INTO precios (nombre, precio, descripcion, iva, precio_total, beneficios) 
                VALUES (:nombre, :precio, :descripcion, :iva, :precio_total, :beneficios)";
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':iva', $iva);
    $stmt->bindParam(':precio_total', $precio_total);
    $stmt->bindParam(':beneficios', $beneficios);

    if ($id) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    if ($stmt->execute()) {
        header('Location: tabla_precios.php');
        exit;
    } else {
        die("Error al guardar el registro: " . $conexion->errorInfo()[2]);
    }
}

// Eliminar un registro si se solicita
if (isset($_GET['eliminar'])) {
    $eliminar_id = (int) $_GET['eliminar'];
    $sql = "DELETE FROM precios WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $eliminar_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: tabla_precios.php');
        exit;
    } else {
        die("Error al eliminar el registro.");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Editar Precio' : 'Crear Nuevo Precio'; ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            color: #4a4a4a;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 15px;
        }

        table thead {
            background-color: #1d2d50;
            color: #fff;
        }

        table thead th, table tbody td {
            padding: 12px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        table tbody tr:nth-child(even) {
            background-color: #f4f6f9;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form label {
            font-weight: bold;
            color: #4a4a4a;
        }

        form input, form textarea {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        form button {
            background-color: #28a745;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $id ? 'Editar Precio' : 'Crear Nuevo Precio'; ?></h1>
        <form method="POST">
            <label for="nombre">Nombre del servicio:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($fila['nombre']); ?>" required>
            
            <label for="precio">Precio base:</label>
            <input type="number" id="precio" name="precio" value="<?php echo htmlspecialchars($fila['precio']); ?>" step="0.01" required>
            
            <label for="descripcion">Descripción del servicio:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($fila['descripcion']); ?></textarea>
            
            <label for="iva">IVA (%):</label>
            <input type="number" id="iva" name="iva" value="<?php echo htmlspecialchars($fila['iva']); ?>" step="0.01" required>

            <label for="beneficios">Beneficios del servicio:</label>
            <textarea id="beneficios" name="beneficios" rows="4"><?php echo htmlspecialchars($fila['beneficios']); ?></textarea>
            
            <button type="submit"><?php echo $id ? 'Actualizar Precio' : 'Crear Precio'; ?></button>
        </form>

        <?php if (!$id): ?>
        <h2>Lista de Precios</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio Base</th>
                    <th>IVA (%)</th>
                    <th>Precio Total</th>
                    <th>Descripción</th>
                    <th>Beneficios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM precios";
                $stmt = $conexion->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    $iva_calculado = $row['precio'] * ($row['iva'] / 100);
                    $precio_total = $row['precio'] + $iva_calculado;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo number_format($row['precio'], 2); ?></td>
                    <td><?php echo $row['iva']; ?>%</td>
                    <td><?php echo number_format($precio_total, 2); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($row['beneficios']); ?></td>
                    <td>
                        <a href="?id=<?php echo $row['id']; ?>">Editar</a>
                        <a href="?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este precio?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>
