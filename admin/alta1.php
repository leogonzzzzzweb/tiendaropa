<?php
// Hace que mysqli lance excepciones (así los errores se capturan con try/catch)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include_once('admin/conexion.php');

$mensaje = "";
$tipo    = "";   // "ok" o "error"

// Valores por defecto para repoblar el formulario si hay error
$nombre = $marca = $color = $talle = $precio = $stock = "";

// Mensaje de éxito luego de la redirección
if (isset($_GET['ok'])) {
    $mensaje = "Producto registrado correctamente.";
    $tipo    = "ok";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ?? '' evita warnings si algún campo no llega; trim saca espacios sobrantes
    $nombre = trim($_POST['nombre'] ?? '');
    $marca  = trim($_POST['marca']  ?? '');
    $color  = trim($_POST['color']  ?? '');
    $talle  = trim($_POST['talle']  ?? '');
    $precio = trim($_POST['precio'] ?? '');
    $stock  = trim($_POST['stock']  ?? '');

    // Validaciones
    if ($nombre === "" || $marca === "" || $color === "" || $talle === "" || $precio === "" || $stock === "") {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo    = "error";
    } elseif (!is_numeric($precio) || $precio < 0) {
        $mensaje = "El precio debe ser un número mayor o igual a 0.";
        $tipo    = "error";
    } elseif (filter_var($stock, FILTER_VALIDATE_INT) === false || $stock < 0) {
        $mensaje = "El stock debe ser un número entero mayor o igual a 0.";
        $tipo    = "error";
    } else {
        try {
            $stmt = $conexion->prepare(
                "INSERT INTO productos (nombre, marca, color, talle, precio, stock)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );

            $precio = (float) $precio;
            $stock  = (int) $stock;

            // s = string, i = integer, d = decimal/double
            // nombre, marca, color, talle = s | precio = d | stock = i
            $stmt->bind_param("ssssdi", $nombre, $marca, $color, $talle, $precio, $stock);

            $stmt->execute();
            $stmt->close();

            // Post/Redirect/Get: evita que al refrescar se duplique el registro
            header("Location: alta.php?ok=1");
            exit;
        } catch (mysqli_sql_exception $e) {
            $mensaje = "Error al registrar el producto.";
            $tipo    = "error";
            // Para depurar, descomentá la siguiente línea (sacala en producción):
            // $mensaje .= " " . $e->getMessage();
        }
    }
}

// Función auxiliar para imprimir valores de forma segura en el HTML
function v($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alta de productos</title>
    <style>
        body  { font-family: Arial, sans-serif; max-width: 420px; margin: 40px auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 6px; box-sizing: border-box; }
        button { margin-top: 15px; padding: 8px 16px; }
        .ok    { color: #0a7a2f; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <h1>Alta de producto</h1>

    <?php if ($mensaje !== ""): ?>
        <p class="<?= $tipo ?>"><?= v($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" action="alta.php">
        <label>Nombre:
            <input type="text" name="nombre" value="<?= v($nombre) ?>" required>
        </label>
        <label>Marca:
            <input type="text" name="marca" value="<?= v($marca) ?>" required>
        </label>
        <label>Color:
            <input type="text" name="color" value="<?= v($color) ?>" required>
        </label>
        <label>Talle:
            <input type="text" name="talle" value="<?= v($talle) ?>" required>
        </label>
        <label>Precio:
            <input type="number" name="precio" step="0.01" min="0" value="<?= v($precio) ?>" required>
        </label>
        <label>Stock:
            <input type="number" name="stock" step="1" min="0" value="<?= v($stock) ?>" required>
        </label>

        <button type="submit" name="enviar">Guardar</button>
    </form>
</body>
</html>